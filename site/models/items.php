<?php
defined('_JEXEC') or die;
 
class CatcomplectsModelItems extends JModelLegacy {

  // Получить все комплектующие
  public function getItems() {
    $db = JFactory::getDbo();
    $query = $db->getQuery(true);

    $query 
        ->select('*')
        ->from($db->quoteName('#__catcomplects_items'))
        ->where($db->quoteName('state').'=1')
        ->order('catid, name, price');
    $db->setQuery($query);

    return $db->loadObjectlist();
  }
  
  // Получить все комплектующие категории
  public function getCatItems($cat_id) {
    $db = JFactory::getDbo();
    $query = $db->getQuery(true);

    $query 
        ->select('*')
        ->from($db->quoteName('#__catcomplects_items'))
        ->where($db->quoteName('catid').'='.$cat_id.' AND '.$db->quoteName('state').'=1')
        ->order('name, price');
    $db->setQuery($query);
    
    return $db->loadObjectlist();
  }
  
  // Получить выбранную комплектующую
  public function getItem($item_id) {
    $db = JFactory::getDbo();
    $query = $db->getQuery(true);

    $query 
        ->select('*')
        ->from($db->quoteName('#__catcomplects_items'))
        ->where($db->quoteName('article').'='.$db->quote($item_id));
    $db->setQuery($query);

    return $db->loadObject();
  }

  // Получить все категории
  public function getCats() {
    $db = JFactory::getDbo();
    $query = $db->getQuery(true);

    $query 
        ->select('*')
        ->from($db->quoteName('#__catcomplects_items_categories'))
        ->where($db->quoteName('state').'=1')
        ->order('ordering');
    $db->setQuery($query);

    return $db->loadObjectlist();
  }

  // Получить выбранную категорию
  public function getCat($cat_id) {
    $db = JFactory::getDbo();
    $query = $db->getQuery(true);

    $query 
        ->select('*')
        ->from($db->quoteName('#__catcomplects_items_categories'))
        ->where($db->quoteName('id').'='.$cat_id);
    $db->setQuery($query);

    return $db->loadObject();
  }


}
