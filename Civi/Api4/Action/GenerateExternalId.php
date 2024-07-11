<?php

namespace Civi\Api4\Action;

use Civi\Api4\Generic\Result;

use CRM_Generateexternalid_ExtensionUtil as E;
use CRM_Generateexternalid_Utils as U;

/**
 * Permet d'effectif les effecti qui sont en doublon dans la zone effectif Représ/Cotis pour une année spécifique
 */
class GenerateExternalId extends \Civi\Api4\Generic\AbstractQueryAction {
    public function _run(Result $result) {
       
    $isLimited = FALSE; //verifier qu'il y a une valeur passer
    $whereContact = [];
    $whereSubType = [];
    $idcontact = null;
    foreach ($this->where as $clause) {
     // $result[] = ['success' => print_r($clause ,1)];
      if ($clause[0] == 'contact_id') {
        $clause[0] = 'id'; // mettre id à la palce de contact_id pour le where de la requete qui suit
        $whereContact[] = $clause[2];
        $idcontact =$clause[2];
        $isLimited = TRUE;
      }
      if ($clause[0] == 'contact_sub_type') {
        $clause[0] = 'id'; // mettre id à la palce de contact_id pour le where de la requete qui suit
        $whereSubType[] = $clause[2];
        $isLimited = TRUE;
      }
    }

    
    if ($isLimited) {
     
      $arrSubType =null;
      $arrContact =null;
      if ($whereSubType) is_array($whereSubType) ? $arrSubType = $whereSubType :  $arrSubType = explode(",",$whereSubType);
      if ($whereContact) is_array($whereContact) ? $arrContact = $whereContact :  $arrContact = explode(",",$whereContact);

      

      $ret[]=[]; $iRet =-1;
      $count = 0;
      $api = \Civi\Api4\Contact::get(FALSE);
      $api->addWhere('contact_type', '=', 'Individual');
      if ($arrSubType) $api->addWhere('contact_sub_type', 'IN', $arrSubType);
      if ($arrContact  AND $idcontact != 0) $api->addWhere('id', 'IN', $arrContact);
      $contacts = $api->execute();

      // $bok = 'avant';
      // if ($arrContact  AND $idcontact != 0){
      //   $bok = 'passed';
      // }
      // $result[] = [
      //   'whereSubType' => $whereSubType,
      //   'arrSubType' => $arrSubType,
      //   'whereContact' => $whereContact,
      //   'arrContact' => $arrContact,
      //   'idcontact' => $idcontact,
      //   'contacts' => $contacts,
      //   '$bok ' => $bok ,
      //   '$$api ' => $api ,
      // ];
      foreach ($contacts as $contact) {
        $count++; $iRet++;
        $bok = U::setIndividualExternalIdentifier($contact);

        $contactRetour = \Civi\Api4\Contact::get(FALSE)
        ->addSelect('id', 'external_identifier', 'last_name', 'first_name', 'middle_name')
        ->addWhere('id', '=', $contact['id'])
        ->setLimit(1)
        ->execute()
        ->first();
      
        $ret[$iRet]['succes'] = $bok;
        $ret[$iRet]['contact'] = $contactRetour;

      }

      $result[] = [
        'count' => $count,
        'return' => $ret,
      ];
    }else{
      $result[] = [];
    }
  }

    public static function fields() {
        return [
          [
            'name' => 'contact_id',
            'data_type' => 'Integer',
            'description' => "Contact ID. Set the value 0 If we want to make all individuals.",
          ],
          [
            'name' => 'contact_sub_type',
            'data_type' => 'string',
            'description' => "Contact Sub type name. Mandatory if the contact_id is not provided. But can be added to the contact_id",
          ],  
        ];
    }
}
