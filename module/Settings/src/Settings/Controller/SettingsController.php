<?php
/*
Copyright (C) 2014 Clayton Daley III

This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

namespace Settings\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\View\Model\ViewModel;
use LegacyRS\Entity;

class SettingsController extends AbstractActionController
{

    public function indexAction()
    {
        $objectManager = $this
            ->getServiceLocator()
            ->get('Doctrine\ORM\EntityManager');
        $repository = $objectManager
            ->getRepository('LegacyRS\Entity\User');
        $user = $repository->find(1);

        $builder = new AnnotationBuilder();
        $form    = $builder->createForm('LegacyRS\Entity\User');

        return new ViewModel(array(
            'form' => $form,
        ));
    }
}