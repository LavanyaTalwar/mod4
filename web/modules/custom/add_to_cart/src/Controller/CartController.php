<?php

namespace Drupal\add_to_cart\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Url;
use Drupal\node\NodeInterface;

/**
 * Handles adding a product to the cart.
 */
class CartController extends ControllerBase {

  /**
   * Adds a product to the cart.
   *
   * @param \Drupal\node\NodeInterface $node
   *   The node being added to the cart.
   *
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   *   Redirects to the product page.
   */
  public function add(NodeInterface $node) {
    \Drupal::messenger()->addMessage($this->t('Product %title has been added to the cart.', ['%title' => $node->getTitle()]));
    $url = Url::fromRoute('entity.node.canonical', ['node' => $node->id()]);
    return new RedirectResponse($url->toString());
  }

}