<?php
/**
 * @version     1.0.0
 * @package     com_catcomplects
 * @copyright   © 2015. Demin Artem. Все права защищены.
 * @license     GNU General Public License версии 2 или более поздней; Смотрите LICENSE.txt
 * @author      Demin Artem <ademin1982@gmail.com> - http://
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Catcomplects.
 */
class CatcomplectsViewComplects extends JViewLegacy {
  protected $items;
  protected $pagination;
  protected $state;

  /**
   * Display the view
   */
  public function display($tpl = null) {
    $this->state = $this->get('State');
    $this->items = $this->get('Items');
    $this->pagination = $this->get('Pagination');

    // Check for errors.
    if (count($errors = $this->get('Errors'))) {
      throw new Exception(implode("\n", $errors));
    }

    CatcomplectsHelper::addSubmenu('complects');
    $this->addToolbar();
    $this->sidebar = JHtmlSidebar::render();
    parent::display($tpl);
  }

  /**
   * Add the page title and toolbar.
   *
   * @since	1.6
   */
  protected function addToolbar() {
    require_once JPATH_COMPONENT . '/helpers/catcomplects.php';

    $state = $this->get('State');
    $canDo = CatcomplectsHelper::getActions($state->get('filter.category_id'));

    JToolBarHelper::title(JText::_('COM_CATCOMPLECTS_TITLE_COMPLECTS'), 'complects.png');

    //Check if the form exists before showing the add/edit buttons
    $formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/complect';
    if (file_exists($formPath)) {
      if ($canDo->get('core.create')) {
        JToolBarHelper::addNew('complect.add', 'JTOOLBAR_NEW');
      }
      if ($canDo->get('core.edit') && isset($this->items[0])) {
        JToolBarHelper::editList('complect.edit', 'JTOOLBAR_EDIT');
      }
    }

    if ($canDo->get('core.edit.state')) {
      if (isset($this->items[0]->state)) {
        JToolBarHelper::divider();
        JToolBarHelper::custom('complects.publish', 'publish.png', 'publish_f2.png', 'JTOOLBAR_PUBLISH', true);
        JToolBarHelper::custom('complects.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
      } else if (isset($this->items[0])) {
        //If this component does not use state then show a direct delete button as we can not trash
        JToolBarHelper::deleteList('', 'complects.delete', 'JTOOLBAR_DELETE');
      }

      if (isset($this->items[0]->state)) {
        JToolBarHelper::divider();
        JToolBarHelper::archiveList('complects.archive', 'JTOOLBAR_ARCHIVE');
      }
      if (isset($this->items[0]->checked_out)) {
        JToolBarHelper::custom('complects.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
      }
    }

    //Show trash and delete for components that uses the state field
    if (isset($this->items[0]->state)) {
      if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
        JToolBarHelper::deleteList('', 'complects.delete', 'JTOOLBAR_EMPTY_TRASH');
        JToolBarHelper::divider();
      } else if ($canDo->get('core.edit.state')) {
        JToolBarHelper::trash('complects.trash', 'JTOOLBAR_TRASH');
        JToolBarHelper::divider();
      }
    }

    if ($canDo->get('core.admin')) {
      JToolBarHelper::preferences('com_catcomplects');
    }

    //Set sidebar action - New in 3.0
    JHtmlSidebar::setAction('index.php?option=com_catcomplects&view=complects');

    $this->extra_sidebar = '';
    //
    JHtmlSidebar::addFilter(
    JText::_('JOPTION_SELECT_PUBLISHED'),
      'filter_published',
      JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), "value", "text", $this->state->get('filter.state'), true)
    );
  }

	protected function getSortFields()
	{
		return array(
		'a.id' => JText::_('JGRID_HEADING_ID'),
		'a.name' => JText::_('COM_CATCOMPLECTS_CATEGORIES_NAME'),
		'a.state' => JText::_('JSTATUS'),
		'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
		);
	}

}
