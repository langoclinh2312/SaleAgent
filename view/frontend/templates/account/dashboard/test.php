<?php if ($_saleAgents && count($_saleAgents)) : ?>
    <div class="table-wrapper agent-recent">
        <table class="data table table-agent-items recent" id="my-agent-table">
            <caption class="table-caption"><?= $block->escapeHtml(__('Sale Agent')) ?></caption>
            <thead>
                <tr>
                    <th scope="col" class="col product-name"><?= $block->escapeHtml(__('Product Name')) ?></th>
                    <th scope="col" class="col product-sku"><?= $block->escapeHtml(__('Product SKU')) ?></th>
                    <th scope="col" class="col commission-type"><?= $block->escapeHtml(__('Commission Type')) ?></th>
                    <th scope="col" class="col commission-value"><?= $block->escapeHtml(__('Commission Value')) ?></th>
                    <th scope="col" class="col actions"><?= $block->escapeHtml(__('View Product')) ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_saleAgents as $_saleAgent) : ?>
                    <?php $_product = $block->getProduct($_saleAgent->getOrderItemSku());
                    if ($_saleAgent->getOrderItemSku() == $_saleAgent->getOrderItemSku()) {
                        echo '<pre>';
                        var_dump($_saleAgent->getOrderItemSku());
                        echo '</pre>';
                    }

                    ?>
                    <tr>
                        <td data-th="<?= $block->escapeHtmlAttr(__('Product Name')) ?>" class="col product-name"><?= $block->escapeHtml($_product->getName()) ?></td>
                        <td data-th="<?= $block->escapeHtmlAttr(__('Product SKU')) ?>" class="col product-sku"><?= $_product->getSku() ?></td>
                        <td data-th="<?= $block->escapeHtmlAttr(__('Product price')) ?>" class="col product-price"><?= $block->getFormatedPrice($_product->getPrice()) ?></td>
                        <td data-th="<?= $block->escapeHtmlAttr(__('Total Assigned')) ?>" class="col Total Assigned"><?= $block->getFormatedPrice($_saleAgent->getTotal()) ?></td>
                        <td data-th="<?= $block->escapeHtmlAttr(__('Actions')) ?>" class="col actions">
                            <a href="<?= $_saleAgent->getProductUrl() ?>" class="action view">
                                <span><?= $block->escapeHtml(__('View Product')) ?></span>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php if ($block->getPagerHtml()) : ?>
        <div class="agent-products-toolbar toolbar bottom"><?= $block->getPagerHtml() ?></div>
    <?php endif; ?>
<?php else : ?>
    <div class="message info empty"><span><?= $block->escapeHtml(__('You have placed no product agent.')) ?></span></div>
<?php endif; ?>

<p>
    SELECT `aht_saleagent_saleagent`.*,
    SUM(`sales_order_item`.`qty_ordered`) as `qty`,
    SUM(
    IF(`aht_saleagent_saleagent`.`commision_type` = 'fixed',
    `aht_saleagent_saleagent`.`commission_value`,
    (`aht_saleagent_saleagent`.`order_item_price` * `aht_saleagent_saleagent`.`commission_value` / 100)
    )
    ) * SUM(`sales_order_item`.`qty_ordered`) as `total`
    FROM `aht_saleagent_saleagent`
    JOIN `sales_order_item` ON `aht_saleagent_saleagent`.`order_item_id` = `sales_order_item`.`item_id`
    Group By `sales_order_item`.`sku`;
</p>