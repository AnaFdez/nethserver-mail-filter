<?php
namespace NethServer\Module\Mail;

/*
 * Copyright (C) 2012 Nethesis S.r.l.
 *
 * This script is part of NethServer.
 *
 * NethServer is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * NethServer is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with NethServer.  If not, see <http://www.gnu.org/licenses/>.
 */

use Nethgui\System\PlatformInterface as Validate;
use Nethgui\Controller\Table\Modify as Table;

/**
 * Mail filter properties for Amavis
 *
 * @author Davide Principi <davide.principi@nethesis.it>
 * @since 1.0
 */
class Filter extends \Nethgui\Controller\AbstractController
{

    public function initialize()
    {
        $this->declareParameter('VirusCheckStatus', Validate::SERVICESTATUS, array('configuration', 'amavisd', 'VirusCheckStatus'));
        $this->declareParameter('SpamCheckStatus', Validate::SERVICESTATUS, array('configuration', 'amavisd', 'SpamCheckStatus'));
        $this->declareParameter('BlockAttachmentStatus', Validate::SERVICESTATUS, array('configuration', 'amavisd', 'BlockAttachmentStatus'));
        $this->declareParameter('SpamSubjectPrefixStatus', Validate::SERVICESTATUS, array('configuration', 'amavisd', 'SpamSubjectPrefixStatus'));
        $this->declareParameter('SpamSubjectPrefixString', $this->createValidator()->maxLength(16), array('configuration', 'amavisd', 'SpamSubjectPrefixString'));
        $this->declareParameter('SpamTagLevel', $this->createValidator()->lessThan(10)->greatThan(2), array('configuration', 'amavisd', 'SpamTagLevel'));
        $this->declareParameter('SpamTag2Level', $this->createValidator()->lessThan(10)->greatThan(2), array('configuration', 'amavisd', 'SpamTag2Level'));
    }

    public function validate(\Nethgui\Controller\ValidationReportInterface $report)
    {
        $this->getValidator('SpamTagLevel')->lessThan($this->parameters['SpamTag2Level']);
        parent::validate($report);
    }

    protected function onParametersSaved($changedParameters)
    {
        $this->getPlatform()->signalEvent('nethserver-mail-filter-save@post-process');
    }

}