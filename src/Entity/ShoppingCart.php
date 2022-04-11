<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ShoppingCart
 *
 * @ORM\Table(name="shopping_cart", indexes={@ORM\Index(name="IDX_72AAD4F6E00EE68D", columns={"id_product_id"}), @ORM\Index(name="IDX_72AAD4F679F37AE5", columns={"id_user_id"})})
 * @ORM\Entity
 */
class ShoppingCart
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="id_user_id", type="integer", nullable=false)
     */
    private $idUserId;

    /**
     * @var int
     *
     * @ORM\Column(name="id_product_id", type="integer", nullable=false)
     */
    private $idProductId;

    /**
     * @var int
     *
     * @ORM\Column(name="nb_product", type="integer", nullable=false)
     */
    private $nbProduct;


}
