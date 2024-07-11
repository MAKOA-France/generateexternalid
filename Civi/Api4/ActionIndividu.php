<?php

namespace Civi\Api4;

/**
 * ActionIndividu API.
 *
 * @package Civi\Api4
 */
class ActionIndividu extends Generic\AbstractEntity {

  public static function getFields($checkPermissions = TRUE) {
    return (new Generic\BasicGetFieldsAction(__CLASS__, __FUNCTION__, function($getFieldsAction) {
      return [
        [
          'name' => 'contact_id',
          'data_type' => 'Integer',
          'description' => "Contact ID",
        ],
      ];
    }))->setCheckPermissions($checkPermissions);
  }


  /**
   * Permet de generer les external idnetifier
   *
   * @param bool $checkPermissions
   *
   * @return Action\ActionIndividu\GenerateExternalId
   */
  public static function generateExternalId($checkPermissions = TRUE) {
    return (new Action\GenerateExternalId(__CLASS__, __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
  }

  /**
   * @return array
   */
  public static function permissions() {
    return [
      'meta' => ['access CiviCRM'],		   
      'default' => ['access CiviCRM'],
    ];
  }
}


