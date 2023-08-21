<?php

namespace Drupal\moderated_content_bulk_publish\Plugin\Action;

//use Drupal\views_bulk_operations\Action\ViewsBulkOperationsActionBase;
//use Drupal\views_bulk_operations\Action\ViewsBulkOperationsPreconfigurationInterface;
use Drupal\node\NodeInterface;
use Drupal\Core\Action\ActionBase;
use Drupal\Core\Plugin\PluginFormInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\moderated_content_bulk_publish\AdminModeration;
use Drupal\Core\Render\Markup;
use Drupal\Core\Access\AccessResult;

/**
 * An example action covering most of the possible options.
 *
 * If type is left empty, action will be selectable for all
 * entity types.
 *
 * @Action(
 *   id = "publish_latest_revision_action",
 *   label = @Translation("Publish Latest Revision"),
 *   type = "node",
 *   confirm = TRUE,
 * )
 */
//only need to add "implements" keywords below if we are goign to add configuration forms to the confirmation step.... not the case here!
class PublishLatestRevisionAction extends ActionBase/*extends ViewsBulkOperationsActionBase implements ViewsBulkOperationsPreconfigurationInterface, PluginFormInterface*/
{



  /**
   * {@inheritdoc}
   */
  public function execute($entity = NULL) {
    /*
     * All config resides in $this->configuration.
     * Passed view rows will be available in $this->context.
     * Data about the view used to select results and optionally
     * the batch context are available in $this->context or externally
     * through the public getContext() method.
     * The entire ViewExecutable object  with selected result
     * rows is available in $this->view or externally through
     * the public getView() method.
     */

    // Do some processing..
    // ...
    //\Drupal::Messenger()->addStatus(utf8_encode('Publish bulk operation by moderated_content_bulk_publish module'));
    $user = \Drupal::currentUser();

    if ($user->hasPermission('moderated content bulk publish')) {
      \Drupal::logger('moderated_content_bulk_publish')->notice("Executing publish latest revision of ".$entity->label());

      $adminModeration = new AdminModeration($entity, NodeInterface::PUBLISHED);
      $entity = $adminModeration->publish($error_message, $msgdetail_isToken, $msgdetail_isPublished, $msgdetail_isAbsoluteURL);
      if (!isset($entity) && !empty($error_message)) {
        // When publish () return NULL, we output messages and to stop the process.
        $msgError = Markup::create(utf8_encode($error_message));
        \Drupal::Messenger()->addWarning($msgError);
        if (!empty($msgdetail_isToken)) {
          $msgToken = Markup::create($msgdetail_isToken);
          \Drupal::messenger()->addWarning($msgToken,TRUE);
        }
        if (!empty($msgdetail_isPublished)) {
          $msgPublished = Markup::create($msgdetail_isPublished);
          \Drupal::messenger()->addWarning($msgPublished,TRUE);
        }
        if (!empty($msgdetail_isAbsoluteURL)) {
          $msgAbsoluteURL = Markup::create($msgdetail_isAbsoluteURL);
          \Drupal::messenger()->addWarning($msgAbsoluteURL,TRUE);
        }
        return $msgError;
      }

      // Check if published
      if (!$entity->isPublished()){
        $msg = "Something went wrong, the entity must be published by this point.  Review your content moderation configuration make sure you have archive state which sets current revision and a published state and try again.";
        \Drupal::Messenger()->addError(utf8_encode($msg));
        \Drupal::logger('moderated_content_bulk_publish')->warning($msg);
        return $msg;
      }
      return sprintf('Example action (configuration: %s)', print_r($this->configuration, TRUE));

    }
    else {
      \Drupal::messenger()->addWarning(t("You don't have access to execute this operation!"));
      return;
    }

  }

  /**
   * {@inheritdoc}
   */
  /*
  public function buildPreConfigurationForm(array $form, array $values, FormStateInterface $form_state) {
    $form['example_preconfig_setting'] = [
      '#title' => $this->t('Example setting'),
      '#type' => 'textfield',
      '#default_value' => isset($values['example_preconfig_setting']) ? $values['example_preconfig_setting'] : '',
    ];
    return $form;
  }
  */

  /**
   * Configuration form builder.
   *
   * If this method has implementation, the action is
   * considered to be configurable.
   *
   * @param array $form
   *   Form array.
   * @param Drupal\Core\Form\FormStateInterface $form_state
   *   The form state object.
   *
   * @return array
   *   The configuration form.
   */
  /*
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form['example_config_setting'] = [
      '#title' => t('Example setting pre-execute'),
      '#type' => 'textfield',
      '#default_value' => $form_state->getValue('example_config_setting'),
    ];
    return $form;
  }
  */

  /**
   * Submit handler for the action configuration form.
   *
   * If not implemented, the cleaned form values will be
   * passed direclty to the action $configuration parameter.
   *
   * @param array $form
   *   Form array.
   * @param Drupal\Core\Form\FormStateInterface $form_state
   *   The form state object.
   */
  /*
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    // This is not required here, when this method is not defined,
    // form values are assigned to the action configuration by default.
    // This function is a must only when user input processing is needed.
    $this->configuration['example_config_setting'] = $form_state->getValue('example_config_setting');
  }
  */

  /**
   * {@inheritdoc}
   */
  public function access($object, AccountInterface $account = NULL, $return_as_object = FALSE) {
    if ($object->getEntityTypeId() === 'node' || $object->getEntityTypeId() === 'media') {
      $moderation_info = \Drupal::service('content_moderation.moderation_information');
      // Moderated Entities will return AccessResult::forbidden for attemps
      // to edit $object->status.
      // @see content_moderation_entity_field_access
      if ($moderation_info->isModeratedEntity($object)) {
        $access = $object->access('update', $account, TRUE)
          ->andIf($object->moderation_state->access('edit', $account, TRUE));
      }
      else {
        $access = $object->access('update', $account, TRUE)
          ->andIf($object->status->access('edit', $account, TRUE));
      }
    }
    else {
      $access = AccessResult::forbidden()->setReason('The chosen Action only acts on entities of type node')->setCacheMaxAge(0);
    }
    return $return_as_object ? $access : $access->isAllowed();
  }

}
