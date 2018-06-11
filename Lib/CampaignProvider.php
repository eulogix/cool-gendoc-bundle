<?php

/*
 * This file is part of the Eulogix\Cool package.
 *
 * (c) Eulogix <http://www.eulogix.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/

namespace Eulogix\Cool\Gendoc\Lib;

use Eulogix\Cool\Lib\DataSource\SimpleValueMap;
use Eulogix\Cool\Lib\Traits\TranslatorHolder;

/**
 * @author Pietro Baricco <pietro@eulogix.com>
 */

abstract class CampaignProvider
{
    use TranslatorHolder;

    /** @var array */
    protected $items;

    /** @var Campaign[] */
    protected $campaigns = [];

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param array $items
     * @return $this
     */
    public function setItems($items)
    {
        $this->items = $items;
        return $this;
    }

    /**
     * @param string $id
     * @param Campaign $campaign
     */
    public function registerCampaign(string $id, Campaign $campaign) {
        $this->campaigns[$id] = $campaign;
    }


    /**
     * @return Campaign[]
     */
    public function getCampaigns(): array
    {
        return $this->campaigns;
    }

    /**
     * @param string $id
     * @return Campaign
     */
    public function getCampaign(string $id)
    {
        return $this->campaigns[$id] ?? null;
    }

    /**
     * returns the available campaigns which the user can select on the provider items
     * @return SimpleValueMap
     * @throws \Exception
     */
    public function getAvailableCampaignTypesValueMap(): SimpleValueMap
    {
        $availableTypes = array_unique(array_map(function(Campaign $campaign){
            return $campaign->getType();
        }, $this->getCampaigns()));

        return new SimpleValueMap($availableTypes, $this->getTranslator());
    }

    /**
     * returns the available campaigns which the user can select on the provider items
     * @return SimpleValueMap
     * @throws \Exception
     */
    public function getAvailableCampaignsValueMap(): SimpleValueMap
    {
        $vm = [];
        foreach($this->getCampaigns() as $id => $campaign) {
            $vm[] = ['value' => $id, 'label' => $campaign->getDescription(), 'type' => $campaign->getType()];
        }
        return new SimpleValueMap($vm);
    }
}