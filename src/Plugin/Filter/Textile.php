<?php

/**
 * @file
 * Contains \Drupal\Textile\Plugin\Filter\Textile.
 */

namespace Drupal\textile\Plugin\Filter;

use Drupal\filter\FilterProcessResult;
use Drupal\filter\Plugin\FilterBase;
use Drupal\filter\Annotation\Filter;
use Drupal\Core\Annotation\Translation;
use Drupal\Component\Utility\String;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Provides a filter for Textile.
 *
 * @Filter(
 *   id = "textile",
 *   module = "textile",
 *   title = @Translation("Textile"),
 *   description = @Translation("Allows content to be submitted using Textile, a simple plain-text syntax that is filtered into valid HTML."),
 *   type = Drupal\filter\Plugin\FilterInterface::TYPE_TRANSFORM_REVERSIBLE,
 * )
 */
class Textile extends FilterBase {

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $library = libraries_detect('textile');

    $form['textile_wrapper'] = array(
      '#type' => 'fieldset',
      '#title' => $this->t('Textile'),
    );

    $url = Url::fromUri($library['vendor url']);
    $external_link = \Drupal::l($library['version'], $url);

    $links = array(
      'Textile Version: ' . $external_link,
    );
    $form['textile_wrapper']['textilestatus'] = array(
      '#title' => $this->t('Versions'),
      '#theme' => 'item_list',
      '#items' => $links,
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function process($text, $langcode) {
    $x = _textile_match_process(array(NULL, $text));
     $result = new FilterProcessResult($x);
     $result->setAssets(array(
      'library' => array(
         'filter/textile',
      ),
    ));

    return $result;
  }

  /**
   * {@inheritdoc}
   */
  public function tips($long = FALSE) {
    if ($long) {
      module_load_include('inc', 'textile', 'textile.tips');
      return _textile_filter_long_tips();
    }
    // elseif (isset($filter->settings['textile_tags']) && $filter->settings['textile_tags']) {
    //   return t('You can use Textile markup to format text between the [textile] and (optional) [/textile] tags.');
    // }
    else {
      return t('You can use Textile markup to format text.');
    }
  }

}