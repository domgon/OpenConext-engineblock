<?php

/**
 * Copyright 2010 SURFnet B.V.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

use OpenConext\EngineBlock\Metadata\Entity\AbstractRole;

class EngineBlock_Corto_Mapper_Metadata_Entity_SsoDescriptor_UiInfo_Keywords
{
    /**
     * @var AbstractRole
     */
    private $_entity;

    public function __construct(AbstractRole $entity)
    {
        $this->_entity = $entity;
    }

    public function mapTo(array $rootElement)
    {
        $keywords = array();
        if ($this->_entity->keywordsEn) {
            $keywords['en'] = $this->_entity->keywordsEn;
        }
        if ($this->_entity->keywordsNl) {
            $keywords['nl'] = $this->_entity->keywordsNl;
        }
        if ($this->_entity->keywordsPt) {
            $keywords['pt'] = $this->_entity->keywordsPt;
        }
        if (empty($keywords)) {
            return $rootElement;
        }

        if (!isset($rootElement['md:Extensions'])) {
            $rootElement['md:Extensions'] = array();
        }
        if (!isset($rootElement['md:Extensions']['mdui:UIInfo'])) {
            $rootElement['md:Extensions']['mdui:UIInfo'] = array(0=>array());
        }
        $uiInfo = &$rootElement['md:Extensions']['mdui:UIInfo'][0];
        if (!isset($uiInfo['mdui:Keywords'])) {
            $uiInfo['mdui:Keywords'] = array();
        }

        foreach ($keywords as $langCode => $value) {
            if (trim($value)==='') {
                continue;
            }

            $uiInfo['mdui:Keywords'][] = array(
                array(
                    '_xml:lang' => $langCode,
                    '__v' => $value,
                ),
            );
        }
        return $rootElement;
    }
}
