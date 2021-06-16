<div id="<?php echo $current_block_ID; ?>-filter" class="query-filter-wrap <?php echo $query_block[$current_block_ID]['display']['filter']['classes']; ?>">

  <div class="query-filter-items">

    <h4 class="query-filter-menu-head mb-3 mb-4 d-flex justify-content-between align-items-end"><?php echo $query_block[$current_block_ID]['display']['filter']['head']; ?> <span class="query-filter-clear">Clear All</span></h4>

    <?php

      foreach ( $query_block[$current_block_ID]['filters'] as $filter ) {

    ?>

    <div class="query-filter">
      <?php

        if ( $filter['type'] == 'post-type' ) {

      ?>

      <h6 class="query-filter-head mb-3">By Content</h6>

      <ul class="query-filter-list" data-multi="false">

        <?php

          foreach ( $query_block[$current_block_ID]['args']['post_type'] as $post_type ) {

            $filter_type = get_post_type_object ( $post_type );
            $filter_type_labels = get_post_type_labels ( $filter_type );

        ?>

        <li class="query-filter-item d-flex align-items-center" data-filter-type="pt" data-filter-value="<?php echo $post_type; ?>">
          <i class="icon far fa-square fa-sm fa-fw mr-2"></i>
          <span class="label"><?php echo $filter_type_labels->singular_name; ?></span>
        </li>

        <?php

          }

        ?>

      </ul>

      <?php

        } elseif ( $filter['type'] == 'taxonomy' ) {

          $filter_tax = get_taxonomy ( $filter['taxonomy'] );
          $filter_tax_labels = get_taxonomy_labels ( $filter_tax );
          $filter_tax_terms = get_terms ( array (
            'taxonomy' => $filter['taxonomy'],
            'hide_empty' => true
          ) );

      ?>

      <h6 class="query-filter-head mb-3">By <?php echo $filter_tax_labels->singular_name; ?></h6>

      <ul class="query-filter-list" data-multi="<?php echo ( $filter['multi'] == 1 ) ? 'true' : 'false'; ?>">

        <?php

          foreach ( $filter_tax_terms as $term ) {

        ?>


        <li class="query-filter-item d-flex align-items-center" data-filter-type="tx" data-filter-key="<?php echo $filter['taxonomy']; ?>" data-filter-value="<?php echo $term->term_id; ?>">
          <i class="icon far fa-square fa-sm fa-fw mr-2"></i>
          <span class="label"><?php echo $term->name; ?></span>
        </li>

        <?php

          }

        ?>

      </ul>

      <?php

        }

      ?>
    </div>

    <?php

      }

    ?>
  </div>
</div>
