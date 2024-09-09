<?php

namespace Drupal\buy_now\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\file\Entity\File;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controller for handling the "Thank You" page after a product purchase.
 */
class BuyProductController extends ControllerBase {

  /**
   * The file URL generator service.
   *
   * @var \Drupal\Core\Url
   */
  protected $fileUrlGenerator;

  /**
   * Constructs a BuyProductController object.
   *
   * @param \Drupal\Core\Url $file_url_generator
   *   The file URL generator service.
   */
  public function __construct($file_url_generator) {
    $this->fileUrlGenerator = $file_url_generator;
  }

  /**
   * Creates an instance of BuyProductController.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   The container interface.
   *
   * @return static
   *   The BuyProductController service.
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('file_url_generator')
    );
  }

  /**
   * Generates and returns the "Thank You" page content.
   *
   * @param int $node
   *   The node ID of the purchased product.
   *
   * @return array
   *   A render array for the "Thank You" page.
   */
  public function thankYouPage($node) {
    $product = \Drupal\node\Entity\Node::load($node);
    $user = \Drupal::currentUser();
    $user_name = $user->getDisplayName();
    $image_urls = [];
    if ($product->hasField('field_images') && !$product->get('field_images')->isEmpty()) {
      foreach ($product->get('field_images') as $image_field) {
        $image_file = File::load($image_field->target_id);
        if ($image_file) {
          $image_urls[] = $this->fileUrlGenerator->generateAbsoluteString($image_file->getFileUri());
        }
      }
    }

    $output_items = [
      $this->t('Thank you @user for purchasing: @product', ['@user' => $user_name, '@product' => $product->label()]),
      $this->t('Quantity: 1'),
    ];

    if (!empty($image_urls)) {
      foreach ($image_urls as $image_url) {
        $output_items[] = $this->t('Product image: <img src="@image" alt="Product Image">', ['@image' => $image_url]);
      }
    }
    else {
      $output_items[] = $this->t('No images available.');
    }

    return [
      '#theme' => 'item_list',
      '#items' => $output_items,
      '#allowed_tags' => ['img'],
    ];
  }

}
