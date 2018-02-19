<?php

namespace Drupal\commerce_franchise\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\commerce_franchise\Entity\FranchiseProductInterface;

/**
 * Class FranchiseProductController.
 *
 *  Returns responses for Franchise product routes.
 */
class FranchiseProductController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Displays a Franchise product  revision.
   *
   * @param int $franchise_product_revision
   *   The Franchise product  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($franchise_product_revision) {
    $franchise_product = $this->entityManager()->getStorage('franchise_product')->loadRevision($franchise_product_revision);
    $view_builder = $this->entityManager()->getViewBuilder('franchise_product');

    return $view_builder->view($franchise_product);
  }

  /**
   * Page title callback for a Franchise product  revision.
   *
   * @param int $franchise_product_revision
   *   The Franchise product  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($franchise_product_revision) {
    $franchise_product = $this->entityManager()->getStorage('franchise_product')->loadRevision($franchise_product_revision);
    return $this->t('Revision of %title from %date', ['%title' => $franchise_product->label(), '%date' => format_date($franchise_product->getRevisionCreationTime())]);
  }

  /**
   * Generates an overview table of older revisions of a Franchise product .
   *
   * @param \Drupal\commerce_franchise\Entity\FranchiseProductInterface $franchise_product
   *   A Franchise product  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(FranchiseProductInterface $franchise_product) {
    $account = $this->currentUser();
    $langcode = $franchise_product->language()->getId();
    $langname = $franchise_product->language()->getName();
    $languages = $franchise_product->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $franchise_product_storage = $this->entityManager()->getStorage('franchise_product');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $franchise_product->label()]) : $this->t('Revisions for %title', ['%title' => $franchise_product->label()]);
    $header = [$this->t('Revision'), $this->t('Operations')];

    $revert_permission = (($account->hasPermission("revert all franchise product revisions") || $account->hasPermission('administer franchise product entities')));
    $delete_permission = (($account->hasPermission("delete all franchise product revisions") || $account->hasPermission('administer franchise product entities')));

    $rows = [];

    $vids = $franchise_product_storage->revisionIds($franchise_product);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\commerce_franchise\FranchiseProductInterface $revision */
      $revision = $franchise_product_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $franchise_product->getRevisionId()) {
          $link = $this->l($date, new Url('entity.franchise_product.revision', ['franchise_product' => $franchise_product->id(), 'franchise_product_revision' => $vid]));
        }
        else {
          $link = $franchise_product->link($date);
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => \Drupal::service('renderer')->renderPlain($username),
              'message' => ['#markup' => $revision->getRevisionLogMessage(), '#allowed_tags' => Xss::getHtmlTagList()],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => $has_translations ?
              Url::fromRoute('entity.franchise_product.translation_revert', ['franchise_product' => $franchise_product->id(), 'franchise_product_revision' => $vid, 'langcode' => $langcode]) :
              Url::fromRoute('entity.franchise_product.revision_revert', ['franchise_product' => $franchise_product->id(), 'franchise_product_revision' => $vid]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.franchise_product.revision_delete', ['franchise_product' => $franchise_product->id(), 'franchise_product_revision' => $vid]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['franchise_product_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
