<?php
/**
 * @var Picker_Item $picker_item
 */
global $picker_item;

// Manage custom picker fields (we are choosing to use a custom picker field if not empty)
$picker_url = ( $picker_item->get_custom_url() != '' ) ? $picker_item->get_custom_url() : $picker_item->get_permalink();
$picker_title = ( $picker_item->get_custom_title() != '' ) ? $picker_item->get_custom_title() : $picker_item->get_title();
$picker_excerpt = ( $picker_item->get_custom_excerpt() != '' )  ? $picker_item->get_custom_excerpt() : $picker_item->get_excerpt( 20, true ); // Tell Picker to truncate after 20 words and use post content if post excerpt is empty
?>

<!-- Display picker item title -->
<h1 class="widget-title picker_title">
    <a href="<?php echo $picker_url ?>" title="<?php echo $picker_title ?>">
        <?php echo $picker_title ?>
    </a>
</h1>

<!-- Display picker item image -->
<?php if ( $picker_item->has_image() ): ?>
    <div class="textwidget picker_image"><?php echo $picker_item->get_image() ?></div>
<?php endif ?>

<!-- Display picker item excerpt -->
<div class="textwidget picker_excerpt"><?php echo $picker_excerpt ?></div>

<!-- Picker item for post <?php echo $picker_item->id ?> from <?php echo ( isset( $use_cache ) && $use_cache ) ? 'cache' : 'database' ?> -->