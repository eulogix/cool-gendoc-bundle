<?php

namespace Eulogix\Cool\Gendoc\Bundle\CWidget\Wizard;

use Eulogix\Cool\Gendoc\Bundle\Model\DocumentJob;
use Eulogix\Cool\Gendoc\Lib\Campaign;
use Eulogix\Cool\Gendoc\Lib\CampaignProvider;
use Eulogix\Cool\Lib\Cool;
use Eulogix\Cool\Lib\DataSource\CoolValueMap;
use Eulogix\Cool\Lib\DataSource\DSRequest;
use Eulogix\Cool\Lib\DataSource\SimpleValueMap;
use Eulogix\Cool\Lib\File\FileRepositoryFactory;
use Eulogix\Cool\Lib\File\FileRepositoryInterface;
use Eulogix\Cool\Lib\File\FileSystemFileRepository;
use Eulogix\Cool\Lib\File\FileUtil;
use Eulogix\Cool\Lib\Form\Field\Select;
use Eulogix\Lib\File\Proxy\FileProxyInterface;
use Eulogix\Lib\FSM\FSMFactory;
use Finite\State\StateInterface;
use Finite\StateMachine\StateMachineInterface;
use Hipoges\Hams\Lib\Cwidget\HAMSFSMForm;

class JobCreateWizardForm extends HAMSFSMForm {

    const STATUS_SELECT_CAMPAIGN = 'selectCampaign';
    const STATUS_SELECT_OPTIONS = 'selectOptions';
    const STATUS_FINAL = 'final';

    /**
     * @var CampaignProvider
     */
    protected $campaignProvider;

    /**
     * @inheritdoc
     */
    public function getId() {
        return "EULOGIX_GENDOC_JOB_WIZARD";
    }

    public function build() {

        parent::build();
        $this->removeField("save");

        try {
            $this->campaignProvider = Cool::getInstance()->getContainer()->get($this->getParameters()->get('provider'));
            if(!($this->campaignProvider instanceof CampaignProvider))
                throw new \Exception("Bad provider");

            $key = $this->getParameters()->get('key');
            $items = Cool::getInstance()->getFactory()->getSharedCacher()->fetch($key);
            $this->getCampaignProvider()->setItems($items);

            $arr = $this->getData();

            //buttons and other stuff that depends on state
            $currentState = $this->getFSM()->getCurrentState();
            $this->buildState($currentState);

            //fill current state form elements with the values in data (if any)
            $this->storeOriginalValues(null, true);
            $this->rawFill( $arr );

            $this->addTransitionButtons();

        } catch(\Exception $e) {
            $this->addMessageError($e->getMessage());
        }

        return $this;
    }

    /**
     * @return FileRepositoryInterface
     * @throws \Exception
     */
    protected function getTempRepository() {
        if($repoKey = $this->getData('tempRepoKey'))
            return FileRepositoryFactory::fromId($repoKey);

        $tempFolder = FileUtil::getTempFolder();
        $tempRepo = new FileSystemFileRepository($tempFolder);
        $repoKey = FileRepositoryFactory::register($tempRepo);
        $this->setInData('tempRepoKey', $repoKey);
        return $tempRepo;
    }

    /**
     * @return StateMachineInterface
     * @throws \Finite\Exception\ObjectException
     */
    protected function buildFSM() {
        $fsm = FSMFactory::simpleLinearFSM([self::STATUS_SELECT_CAMPAIGN, self::STATUS_SELECT_OPTIONS, self::STATUS_FINAL], $this);
        return $fsm;
    }

    /**
     * adds to the form the elements that belong to a given FSM state
     * @param StateInterface $state
     * @throws \Exception
     */
    private function buildState(StateInterface $state) {
        $this->getAttributes()->set('state', $state->getName());

        switch($state->getName()) {
            case self::STATUS_SELECT_CAMPAIGN : {

                $this->addFieldSelect('campaign_type')
                    ->setValueMap($this->getCampaignProvider()->getAvailableCampaignTypesValueMap())
                    ->setNotNull();

                $this->addFieldSelect('campaign')
                    ->setValueMap($this->getCampaignProvider()->getAvailableCampaignsValueMap())
                    ->setNotNull();

                /** @var Select $ct */
                $ct = $this->getField('campaign_type');
                $ct->filterAnotherField('campaign', 'type');

                break;
            }

            case self::STATUS_SELECT_OPTIONS : {

                $templates = $this->getCampaign()->getTemplates();
                $values = [];
                /** @var FileProxyInterface $template */
                foreach($templates->getIterator() as $template) {
                    $values[] = [
                        'label' => $template->getName(),
                        'value' => $template->getId()
                    ];
                }

                $this->addFieldSelect('existing_template')
                    ->setValueMap(new SimpleValueMap($values))
                    ->setOnChange('widget.mixAction(\'refreshTemplate\');');

                if($tempTemplate = $this->getTempTemplate()) {

                    $itemData = $this->getItemData(0);

                    $this->addFieldButton('editTemplate')
                        ->setOnClick("
                         var d = COOL.getDialogManager().openWidgetDialog(
                            'EulogixCoolCoreBundle/Files/Editor/TwigTemplateEditorForm',
                            'Edit twig template',
                            ".json_encode([
                                'filePath' => '/'.$tempTemplate->getName(),
                                'repositoryParameters' => json_encode(['repositoryId' => $this->getData('tempRepoKey')]),
                                'hideCloseButton' => true
                            ])."
                            ,function(){ d.widget.mixAction('cleanup'); },
                            null,
                            function(form) {
                                form.set('sampleData', ".json_encode($itemData).");
                           }
                         );
                     ");
                }

                break;
            }

            case self::STATUS_FINAL : {

                $snippetsVmap = CoolValueMap::getValueMapForTable('core', 'core.code_snippet');

                $this->addFieldTextBox('job_name')->setNotNull();
                $this->addFieldTextBox('job_description')->setNotNull();
                $this->addFieldSelect('doc_snippet')
                    ->setValueMap(
                        $snippetsVmap->filterByAllowedValues($this->getCampaign()->getAvailableGenerationSnippetIds())
                    )->setNotNull();

                $this->addFieldSelect('schedule_weekdays')
                    ->setValueMap(
                        new SimpleValueMap([
                            'AllWeek' => DocumentJob::SCHEDULE_DAYS_ALLWEEK,
                            'MondayToFriday' => DocumentJob::SCHEDULE_DAYS_MONDAY_TO_FRIDAY,
                            'Weekends' => DocumentJob::SCHEDULE_DAYS_WEEKEND
                        ], $this->getTranslator())
                    )->setNotNull();

                $this->addFieldSelect('schedule_hours')
                    ->setValueMap(
                        new SimpleValueMap([
                            '8_to_17' => DocumentJob::SCHEDULE_HOURS_8_TO_17,
                            '0_to_23' => DocumentJob::SCHEDULE_HOURS_ALL_DAY,
                            '7_to_21' => DocumentJob::SCHEDULE_HOURS_DAY_HOURS,
                            '22_to_6' => DocumentJob::SCHEDULE_HOURS_NIGHT_HOURS
                        ], $this->getTranslator())
                    )->setNotNull();

                $this->addFieldNumber('documents_per_iteration');

                $this->addFieldNumber('minutes_between_iterations');

                $this->addFieldSubmit("save");
                break;
            }
        }
    }

    /**
     * @throws \Exception
     */
    public function onRefreshTemplate() {
        $parameters = $this->request->all();
        $this->rawFill( $parameters );
        $this->updateDataFromFieldValues();

        if($template = $this->getSelectedTemplate()) {
            $this->getTempRepository()->storeFileAt($template);
            $tempTplId = '/'.$template->getName();
        } else $tempTplId = null;

        $this->setInData('temp_template_id', $tempTplId);

        $this->reBuild();
    }


    public function onSubmit() {

        $parameters = $this->request->all();
        $this->rawFill( $parameters );

        try {
            if($this->validate( array_keys($parameters) ) ) {
                $this->updateDataFromFieldValues();

                $rd = Cool::getInstance()->getFactory()->getRundeck();
                if( $jobId = $rd->getJobIdByName($jobName = 'cool:gendoc:createjob') ) {

                    $cacher = Cool::getInstance()->getFactory()->getSharedCacher();
                    $inputKey = md5(microtime());

                    $cacher->store($inputKey, [
                        'executionEnvironment' => serialize(Cool::getInstance()->getExcutionEnvironment()),
                        'formData' => $this->getData(),
                        'campaign' => serialize($this->getCampaign()),
                        'campaignProvider' => serialize($this->getCampaignProvider()),
                    ]);


                    if ($execution = $rd->runJob( $jobId, [
                        'input_key' => $inputKey
                    ])) {
                        $exec = array_pop($execution);
                        $this->addMessageInfo("Job is being created with execution id ".$exec['id']);
                        $this->setReadOnly(true);
                    }
                } else throw new \Exception("missing {$jobName} Rundeck job");

            } else {
                $this->addMessageError("NOT VALIDATED");
            }
        } catch (\Exception $e) {
            $this->addMessageError($e->getMessage());
        }

    }

    /**
     * @return CampaignProvider
     */
    protected function getCampaignProvider() {
        return $this->campaignProvider;
    }

    /**
     * @return Campaign
     */
    protected function getCampaign() {
        $data = $this->getData();
        return $this->campaignProvider->getCampaign(@$data['campaign']);
    }

    /**
     * @return FileProxyInterface|null
     */
    protected function getSelectedTemplate() {
        $data = $this->getData();
        if(($c = $this->getCampaign()) && @$data['existing_template'])
            return $c->getTemplate($data['existing_template']);
        return null;
    }

    /**
     * @return FileProxyInterface|null
     */
    protected function getTempTemplate() {
        try {
            if($tempTplId = $this->getData('temp_template_id'))
                return $this->getTempRepository()->get($tempTplId);
        } catch (\Exception $e) {}
        return null;
    }

    /**
     * @param int $index
     * @return mixed
     */
    private function getItemData($index)
    {
        return $this->getCampaign()->getDataSource()->getDSRecord(
            $this->getCampaignProvider()->getItems()[$index],
            [],
            function(DSRequest $request) {
                $request->setIncludeDecodings(true);
            }
        )->getValues();
    }
}