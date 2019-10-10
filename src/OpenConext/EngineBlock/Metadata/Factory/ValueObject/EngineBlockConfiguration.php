<?php declare(strict_types=1);

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

namespace OpenConext\EngineBlock\Metadata\Factory\ValueObject;

use OpenConext\EngineBlock\Metadata\ContactPerson;
use OpenConext\EngineBlock\Metadata\Logo;
use OpenConext\EngineBlock\Metadata\Organization;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Contains the EngineBlock information that is required to generate SP and IdP XML metadata
 *
 * @see: OpenConext/EngineBlock/Metadata/Factory/Factory
 */
class EngineBlockConfiguration
{
    /**
     * @var string
     */
    private $suiteName;

    /**
     * @var Logo
     */
    private $logo;

    /**
     * @var string
     */
    private $supportUrl;

    /**
     * @var string
     */
    private $supportMail;

    /**
     * @var string
     */
    private $description;

    /**
     * @var ContactPerson[]
     */
    private $contactPersons;

    public function __construct(
        TranslatorInterface $translator,
        string $supportUrl,
        string $supportMail,
        string $description,
        string $logoUrl,
        int $logoWidth,
        int $logoHeight
    ) {
        $this->suiteName = $translator->trans('suite_name');
        $this->supportUrl = $supportUrl;
        $this->supportMail = $supportMail;
        $this->description = $description;
        // A logo VO is created during construction time
        $this->logo = new Logo($logoUrl);
        $this->logo->width = $logoWidth;
        $this->logo->height = $logoHeight;

        // Create the contact person data for the EB SP entity
        $support = ContactPerson::from('support', $this->suiteName, 'Support', $this->supportMail);
        $technical = ContactPerson::from('technical', $this->suiteName, 'Support', $this->supportMail);
        $administrative = ContactPerson::from('administrative', $this->suiteName, 'Support', $this->supportMail);

        $this->contactPersons = [$support, $technical, $administrative];
    }

    public function getName(): string
    {
        return $this->suiteName . ' EngineBlock';
    }

    public function getOrganization() : Organization
    {
        return new Organization($this->suiteName, $this->suiteName, $this->supportUrl);
    }

    public function getLogo(): Logo
    {
        return $this->logo;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getContactPersons() : array
    {
        return $this->contactPersons;
    }
}
