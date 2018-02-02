<?php

/*
 * This file is part of the Eulogix\Cool package.
 *
 * (c) Eulogix <http://www.eulogix.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/

namespace Eulogix\Cool\Bundle\GendocBundle\Tests;

use Eulogix\Cool\Bundle\CoreBundle\Tests\Cases\baseTestCase;
use Eulogix\Cool\Bundle\GendocBundle\Model\QueuedDocument;
use Eulogix\Cool\Lib\Cool;
use Eulogix\Cool\Lib\File\FileRepositoryFactory;

/**
 * @author Pietro Baricco <pietro@eulogix.com>
 */

class BaseTest extends baseTestCase
{
    const TEMPLATE_REPOSITORY_ID = 'gendoc_test_templates';
    const TEMPLATE_1 = '/template1.zip';
    const TEMPLATE_2 = '/template2.zip';

    public function testGendoc() {

        Cool::getInstance()->getSchema('gendoc')->query("TRUNCATE table queued_document");

        $repo = FileRepositoryFactory::fromId(self::TEMPLATE_REPOSITORY_ID);
        $overriddenTemplate = $repo->get(self::TEMPLATE_2);

        /**
         * @var QueuedDocument[] $docs
         */
        $docs = [];
        for($i=0; $i<5; $i++) {
            $doc = new QueuedDocument();
            $doc->setTemplateRepositoryId(self::TEMPLATE_REPOSITORY_ID)
                ->setMasterTemplate(self::TEMPLATE_1)
                ->setOutputFormat('pdf')
                ->setOverrideableFlag(true)
                ->setData([
                    'var1' => $i,
                    'var2' => 'string'.$i
                ]);
            $doc->save();

            if($i%2)
                $doc->setCustomTemplate($overriddenTemplate);

            $docs[ $i ] = $doc;
        }

        foreach($docs as $i => $doc) {
            $renderedFile = $doc->render();

            if($i%2) $this->assertLessThan(5000, $renderedFile->getSize());
            else $this->assertGreaterThan(10000, $renderedFile->getSize());
        }

    }
}
