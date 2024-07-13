<?php

require_once 'generateexternalid.civix.php';
// phpcs:disable
use CRM_Generateexternalid_ExtensionUtil as E;
use CRM_Generateexternalid_Utils as U;
// phpcs:enable

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function generateexternalid_civicrm_config(&$config): void {
  _generateexternalid_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function generateexternalid_civicrm_install(): void {
  _generateexternalid_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function generateexternalid_civicrm_enable(): void {
  _generateexternalid_civix_civicrm_enable();
}

function generateexternalid_civicrm_post(string $op, string $objectName, int $objectId, &$objectRef){

  if($objectName == 'Individual' && ($op == 'create' || $op == 'edit')){
    if($objectRef && $objectId){
      if (is_object($objectRef) && get_class($objectRef) == 'CRM_Contact_DAO_Contact'){
        U::setIndividualExternalIdentifier($objectRef);
      }
    }
  }
}