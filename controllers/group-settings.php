<?php
/*
********************************************************
* @author Santos Montano B. (Lito Santos M.)
* @author_url 1: http://www.kanorika.com
* @author_url 2: http://codecanyon.net/user/kanorika
* @author_email: info@kanorika.com   
********************************************************
* iSocial - Social Networking Platform
* Copyright (c) 2018 iSocial. All rights reserved.
********************************************************
*/
	if (!$D->_IS_LOGGED) $this->globalRedirect($K->SITE_URL.$this->params->username);

    $D->_IN_PROFILE = TRUE;

    $D->_WITH_NOTIFIER = TRUE;

	$D->isPhantom = FALSE;
	if ($this->param('phantom') && $this->param('phantom')=='yes') $D->isPhantom = TRUE;

	$D->layout_size = 'min';
	if ($this->param('lysize')) $D->layout_size = $this->param('lysize');

	$this->loadLanguage('global.php');
    $this->loadLanguage('dashboard.php');
    $this->loadLanguage('settings.php');
    $this->loadLanguage('profile.php');

    $thegroup = $network->getGroupById($this->params->group);
    $D->idgroup = $thegroup->idgroup;
    $D->codegroup = $thegroup->code;
    $D->idcreator = $thegroup->idcreator;
    $D->username = $thegroup->guname;
    $D->the_title = $thegroup->title;
    $D->about = $thegroup->about;

    $D->im_member = FALSE;

    $D->me = $this->user->info;
    $D->is_my_group = ($D->idcreator == $D->me->iduser);
    $D->membership = $this->user->membership($D->idgroup);
    if ($D->membership == 1) $D->im_member = TRUE;

    if (!$D->is_my_group) $this->globalRedirect($K->SITE_URL.$this->params->username);

    $D->privacy = $thegroup->privacy;
    $D->text_type_group = '';
    $D->ico_type_group = '';
    switch($D->privacy) {
        case 0:
            $D->text_type_group = $this->lang('global_type_group_public');
            $D->ico_type_group = 'ico-group-public.png';
            break;
        case 1:
            $D->text_type_group = $this->lang('global_type_group_closed');
            $D->ico_type_group = 'ico-group-closed.png';
            break;
        case 2:
            $D->text_type_group = $this->lang('global_type_group_secret');
            $D->ico_type_group = 'ico-group-secret.png';
            break;
    }

    $D->with_cover = FALSE;
    $D->cover_group = '';
    if (!empty($thegroup->cover)) {
        $D->cover_group = $K->STORAGE_URL_COVERS_GROUP.$thegroup->code.'/'.$thegroup->cover;
        $D->with_cover = TRUE;
		$D->cover_media = $thegroup->cover_media;
		$D->cover_user = $thegroup->cover_user;
    }

    $D->position_cover_group = $thegroup->cover_position;
    $D->the_register_date = $thegroup->created;

    $D->id_container = 'dashboard';
    $this->load_extract_controller('_dashboard-menu-left');
    
    /****************************************************************************/

    $this->load_extract_controller('_pre-profile');

    /****************************************************************************/

	if ($D->isPhantom) {

        $html = '';

        if ($D->layout_size == 'min') {
            $for_load = 'min/group-settings.php';
        } else {
            $for_load = 'max/group-settings.php';
        }

        $D->titlePhantom = $D->the_title.' | '.$K->SITE_TITLE;

        $html .= $this->load_template($for_load, FALSE);
        echo $html;

	} else {

        $D->page_title = $D->the_title.' | '.$K->SITE_TITLE;

        $this->load_extract_controller('_required-dashboard');
        $this->load_extract_controller('_dashboard-bar-top');

        $D->file_in_template = 'max/group-settings.php';            
        $this->load_template('dashboard-template.php');

    }

?>