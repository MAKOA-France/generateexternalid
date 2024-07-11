# generateexternalid

 This extension allows you to create an external identifier for individual contact types. 
  It concatenates the last name and first names in lowercase. For each channel it:
  - remove special characters
  - replaces accented characters with their non-accented characters
  - remove spaces

  This is done each time a contact is created or modified.

  An API4 ActionIndividu > GenerateExternalId allows you to do this individually or in batches with the following parameters:
  - contact_id: identifier of the contact where we want to regenerate the external identifier. Set the value 0 If we want to make all individuals.

  - contact_sub_type: Contact subtype of individuals where we want to regenerate the external identifier.
  Mandatory if the contact_id is not provided. But can be added to the contact_id
The extension is licensed under [AGPL-3.0](LICENSE.txt).

## Requirements

* PHP v7.4+
* CiviCRM (*FIXME: Version number*)

## Installation (Web UI)

Learn more about installing CiviCRM extensions in the [CiviCRM Sysadmin Guide](https://docs.civicrm.org/sysadmin/en/latest/customize/extensions/).

## Installation (CLI, Zip)

Sysadmins and developers may download the `.zip` file for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
cd <extension-dir>
cv dl generateexternalid@https://github.com/FIXME/generateexternalid/archive/master.zip
```
or
```bash
cd <extension-dir>
cv dl generateexternalid@https://lab.civicrm.org/extensions/generateexternalid/-/archive/main/generateexternalid-main.zip
```

## Installation (CLI, Git)

Sysadmins and developers may clone the [Git](https://en.wikipedia.org/wiki/Git) repo for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
git clone https://github.com/FIXME/generateexternalid.git
cv en generateexternalid
```
or
```bash
git clone https://lab.civicrm.org/extensions/generateexternalid.git
cv en generateexternalid
```

## Getting Started

(* FIXME: Where would a new user navigate to get started? What changes would they see? *)

## Known Issues

(* FIXME *)
