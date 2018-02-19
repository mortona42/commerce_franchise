<?php

namespace Drupal\commerce_franchise\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class FranchiseProductTypeForm.
 */
class FranchiseProductTypeForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $franchise_product_type = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $franchise_product_type->label(),
      '#description' => $this->t("Label for the Franchise product type."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $franchise_product_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\commerce_franchise\Entity\FranchiseProductType::load',
      ],
      '#disabled' => !$franchise_product_type->isNew(),
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $franchise_product_type = $this->entity;
    $status = $franchise_product_type->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Franchise product type.', [
          '%label' => $franchise_product_type->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Franchise product type.', [
          '%label' => $franchise_product_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($franchise_product_type->toUrl('collection'));
  }

}
