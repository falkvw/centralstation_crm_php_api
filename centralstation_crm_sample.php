<?php
/**
 * Class centralstation_crm_sample
 */
class centralstation_crm_sample {

    private $crm;

    /**
     * centralstation_crm_use constructor.
     */
    public function __construct()
    {
        $this->crm              = new centralstation_crm();

        $aOutput                = array();
        $aOutput['company']     = self::createCompany('');
        $aOutput['note']        = self::createCompanyNote($aOutput['company']['company']['id'],'');
        $aOutput['tag']         = self::createCompanyTag($aOutput['company']['company']['id'],'Sample Tag');
        $aOutput['task']        = self::createCompanyTask($aOutput['company']['company']['id'],'');

        echo '<pre>';
        print_r($aOutput);
        echo '</pre>';
    }

    /**
     * @param $aEntity
     * @return array|bool
     */
    public function createCompany($aEntity)
    {
        $aEntity                    = array();
        $aEntity['company']['name'] = 'Testfirma '.date('h:i');
        $aEntity                    = $this->crm->createEntity('companies',$aEntity);

        $aContact = array();
        $aContact['email']['atype'] = 'office';
        $aContact['email']['name']  = 'sample@example.com';
        $aEntity['email']           = $this->crm->createEntitySubDetail('companies',$aEntity['company']['id'],'contact_details',$aContact);

        $aContact['tel']['atype']   = 'office';
        $aContact['tel']['name']    = '0123456789';
        $aEntity['tel']             = $this->crm->createEntitySubDetail('companies',$aEntity['company']['id'],'contact_details',$aContact);

        $aAddress = array();
        $aAddress['addr']['atype']  = 'work_hq';
        $aAddress['addr']['street'] = 'Street';
        $aAddress['addr']['city']   = 'City';
        $aAddress['addr']['zip']    = '12345';
        $aEntity['addr_hq']            = $this->crm->createEntitySubDetail('companies',$aEntity['company']['id'],'addrs',$aAddress);

        $aAddress = array();
        $aAddress['addr']['atype']  = 'invoice';
        $aAddress['addr']['street'] = 'Street';
        $aAddress['addr']['city']   = 'City';
        $aAddress['addr']['zip']    = '12345';
        $aEntity['addr_inv']        = $this->crm->createEntitySubDetail('companies',$aEntity['company']['id'],'addrs',$aAddress);

        return $aEntity;
    }

    /**
     * @param $szCompanyId
     * @param $aNote
     * name 	VARCHAR(60) 	Anriss der Notiz (automatisch gesetzt)
     * content 	TEXT 	        Inhalt der Notiz (Pflichtfeld)
     * badge 	VARCHAR(20) 	Art der Notiz (note, call, email, meeting, other oder research bei Firmen / companies)
     * @return bool
     */
    public function createCompanyNote($szCompanyId,$aNote)
    {
        $aContact                           = array();
        $aContact['protocol']['badge']      = 'note';
        $aContact['protocol']['name']       = 'Passwort geändert';
        $aContact['protocol']['content']    = 'Der User hat sein Passwort geändert.';
        $aEntity                            = $this->crm->createEntitySubDetail('companies',$szCompanyId,'protocols',$aContact);

        return $aEntity;
    }

    /**
     * @param $szCompanyId
     * @param $szTag
     * name 	VARCHAR(60) 	Name des Tags (Pflichtfeld)
     * @return bool
     */
    public function createCompanyTag($szCompanyId,$szTag)
    {
        $aTag['tag']['name']    = $szTag;
        $aEntity                = $this->crm->createEntitySubDetail('companies',$szCompanyId,'tags',$aTag);

        return $aEntity;
    }

    /**
     * @param $szCompanyId
     * @param $aTask
     * attachable_id 	    INTEGER 	    ID des verknüpften Objektes, zum Beispiel Person, Firma, Angebot oder Projekt.
     * attachable_type 	    VARCHAR(20) 	Typ des verknüpften Objektes, z.B. Person, Company, Deal oder Project.
     * badge 	            VARCHAR(20) 	Kategorie der Aufgabe. Möglich sind: task, call, meeting, email, preparation und other.
     * name 	            VARCHAR(255) 	Text / Beschreibung der Aufgabe (Pflichtfeld)
     * precise_time 	    TIMESTAMP 	    Zeitpunkt der Fälligkeit
     * user_id 	            INTEGER 	    ID des verantwortlichen Users, wenn die Aufgabe zu einem User gehört (automatisch gesetzt).
     * created_by_user_id 	INTEGER 	    ID des Users, der die Aufgabe angelegt hat (automatisch gesetzt).
     * @return bool
     */
    public function createCompanyTask($szCompanyId,$aTask)
    {
        $aTask                                  = array();
        $aTask['task']['badge']                 = 'task';
        $aTask['task']['user_id']               = '12345';
        $aTask['task']['attachable_id']         = $szCompanyId;
        $aTask['task']['attachable_type']       = 'Company';
        $aTask['task']['precise_time']          = date('Y-m-dTh:i:s.000+01:00',strtotime('+3 days'));
        $aTask['task']['created_by_user_id']    = '12345';
        $aTask['task']['name']                  = 'Bitte das Passwort verändern, damit man es nicht mehr herausfinden kann';
        $aEntity                                = $this->crm->createEntity('tasks',$aTask);

        return $aEntity;
    }
}