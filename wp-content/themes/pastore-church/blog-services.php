<?php
/*
Template Name: Services list
*/

/**
 * Make empty page with this template 
 * and put it into menu
 * to display all Services as streampage
 */

pastore_church_storage_set('blog_filters', 'services');

get_template_part('blog');
?>