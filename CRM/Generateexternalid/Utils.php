<?php

class CRM_Generateexternalid_Utils {

    /**
     * Update the external identifier for individual
     * 
     * @params $contact CRM_Contact_DAO_Contact of individual OR array contact
     * @return [boolean] 
     */
    public static function setIndividualExternalIdentifier($contact){
        if(is_object($contact)){
            $params['id'] = $contact->id;
            $params['last_name'] = $contact->last_name;
            $params['first_name'] = $contact->first_name;
            $params['middle_name'] = $contact->middle_name;
            $params['external_identifier'] = $contact->external_identifier;
            
        }else {
            $params['id'] = $contact['id'];
            $params['last_name'] = $contact['last_name'];
            $params['first_name'] = $contact['first_name'];
            $params['middle_name'] = $contact['middle_name'];
            $params['external_identifier'] = $contact['external_identifier'];
        }
        return self::setExternalID($params);

    }
    public static function setExternalID($params){
        // Get variable and clean string
        $id = $params['id'];
        $external_identifier =  self::checkstring($params['external_identifier']);
        $last_name =  self::checkstring($params['last_name']);
        $first_name =  self::checkstring($params['first_name']);
        $middle_name = self::checkstring($params['middle_name']);       
            
        // Build the external identifiant
        $str = $last_name.$first_name;
        if (!is_null($middle_name)) $str .= $middle_name;
        $strFormatted = self::remove_accents_and_special_chars($str,"");
        $strFormatted = strtolower($strFormatted);

        $strFormattedPlusID = $strFormatted . '-'.$id;

        // if external identifier is different to this on bdd and different if added id at the end
        if( $external_identifier != $strFormatted && $external_identifier != $strFormattedPlusID){

            //Verfiier si l'exteranlId exidt deja pour un autre contact ?
            $contact_id = 0;
            $contact_other = \Civi\Api4\Contact::get(FALSE)
            ->addWhere('external_identifier', '=', $strFormatted)
            ->addWhere('id', '<>', $id )
            ->execute()->first();
            // another contact has this external id so add their id at the end
            if ($contact_other) $strFormatted .= '-'.$id;

            // update external identifier
            $api = \Civi\Api4\Contact::update(FALSE);
            $api->addValue('first_name',$first_name);
            if (!empty($middle_name)) $api->addValue('middle_name', $middle_name);
            $api->addValue('last_name', $last_name);
            $api->addValue('external_identifier', $strFormatted);
            $api->addWhere('id', '=', $id);
            $resul = $api->execute();
          return true;
        }

        return false;
    }

    /**
     * Allows you to replace all stressed vowels with their unstressed vowels. And replace all other characters with $replace
     * 
     * @params $str [string] character string to process
     * @params $replacespecial [string] Character that replaces characters other than a console or vowel
     * @return [string]
     */
    public static function remove_accents_and_special_chars($str, $relacespecial = "") {
        // Remplacer les caractères accentués par leurs équivalents sans accent
        $unwanted_array = array(
            'à' => 'a', 'â' => 'a', 'ä' => 'a', 'á' => 'a', 'ã' => 'a', 'å' => 'a', 'ā' => 'a',
            'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ē' => 'e', 'ė' => 'e', 'ę' => 'e',
            'î' => 'i', 'ï' => 'i', 'í' => 'i', 'ī' => 'i', 'į' => 'i', 'ì' => 'i',
            'ô' => 'o', 'ö' => 'o', 'ò' => 'o', 'ó' => 'o', 'õ' => 'o', 'ø' => 'o', 'ō' => 'o',
            'û' => 'u', 'ü' => 'u', 'ù' => 'u', 'ú' => 'u', 'ū' => 'u',
            'ÿ' => 'y', 'ý' => 'y',
            'ç' => 'c', 'ć' => 'c', 'č' => 'c',
            'ñ' => 'n', 'ń' => 'n',
            'š' => 's', 'ś' => 's',
            'ž' => 'z', 'ź' => 'z', 'ż' => 'z',
            'þ' => 'th', 'ß' => 'ss',
            'Œ' => 'OE', 'œ' => 'oe',
            'Æ' => 'AE', 'æ' => 'ae',
            'Å' => 'A', 'Ø' => 'O'
        );
    
        $str = strtr($str, $unwanted_array);
    
        // Utiliser iconv pour transformer en caractères ASCII
        $str = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $str);
    
        // Supprimer les caractères NON alphanumériques et remplacer par $relacespecial
        $str = preg_replace('/[^a-zA-Z0-9\s]/', $relacespecial, $str);
    
        // Remplacer plusieurs espaces par $relacespecial
        $str = preg_replace('/\s+/', $relacespecial, $str);
    
        // Supprimer les espaces en début et fin de chaîne
        $str = trim($str);
    
        return $str;
    }

    public static function checkstring($str){

        if( strtolower($str) == 'null') $str = '';
        return $str;
    }
}