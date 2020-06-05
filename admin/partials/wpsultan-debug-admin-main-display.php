<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://wp-sultan.com
 * @since      1.0.0
 *
 * @package    Wpsultan_Debug
 * @subpackage Wpsultan_Debug/admin/partials
 */

 // If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}

if ( !empty( $_GET['active'] ) && '1' == $_GET['active'] && wp_verify_nonce( $_GET['_wpnonce'], 'active_wb_debug' ) ) {

	$this->active();

}

if ( true === $this->wp_debug_ready ) {

	function get_between( $input, $start, $end ) {
		$substr = substr( $input, strlen( $start ) + strpos( $input, $start ), ( strlen( $input ) - strpos( $input, $end ) ) * ( -1 ) );
		return $substr;
	}
	function get_from_char( $input, $start ) {
		$substr = substr( $input, strlen( $start ) + strpos( $input, $start ) );
		return $substr;
	}
	$debug_file = $this->get_debug_file();
	if ( '/debug.log' === $debug_file ) {
		$section = file_get_contents( WP_CONTENT_DIR . $debug_file );
	} else {
		$section = file_get_contents( ABSPATH . $debug_file );
	}
	$array     = array_reverse( explode( PHP_EOL, $section ) );
	$arr_count = count( $array );
	?>
<div class="wrap">
    <div class="">
        <h2 class=""><?php _e( 'My Debug File Table', 'wpsultan-debug' );?></h2>
        <span class="badge badge-primary mb-3"><?php _e( 'Powered by : ', 'wpsultan-debug' ); ?><a target="_blank" href="https://wp-sultan.com"><span class="text-light"> <?php _e( ' WP SULTAN', 'wpsultan-debug' ); ?></span></a></span>
    </div>
    <table class="widefat fixed table" cellspacing="0">
        <thead>
            <tr>
                <th width="20%"><?php _e( 'Date | Time | TimeZone', 'wpsultan-debug' );?></th>
                <th width="10%"><?php _e( 'Error', 'wpsultan-debug' );?></th>
                <th><?php _e( 'Message', 'wpsultan-debug' );?></th>
            </tr>
        </thead>

        <tfoot>
            <tr>
                <th><?php _e( 'Date and Time', 'wpsultan-debug' );?></th>
                <th><?php _e( 'Error', 'wpsultan-debug' );?></th>
                <th><?php _e( 'Message', 'wpsultan-debug' );?></th>
            </tr>
        </tfoot>

        <tbody>
            <?php
function currentUrl() {
		$protocol = strpos( strtolower( $_SERVER['SERVER_PROTOCOL'] ), 'https' ) === false ? 'http' : 'https';
		$host     = $_SERVER['HTTP_HOST'];
		$script   = $_SERVER['SCRIPT_NAME'];
		$params   = $_SERVER['QUERY_STRING'];

		return $protocol . '://' . $host . $script . '?' . $params;
	}
	function pagination_arr( $item_count, $cur_page, $limit = 20 ) {
		$page_count = ceil( $item_count / $limit );
		$offset     = ( $cur_page - 1 ) * $limit;
		return [
			"total"  => $page_count,
			"offset" => $offset,
		];
	}
	$i          = 0;
	$page_limit = 10;
	if ( isset( $_GET['paged'] ) ) {
		$page = $_GET['paged'];
	} else {
		$page = 1;
	}
	$paginate   = pagination_arr( $arr_count, $page, $page_limit );
	$arr_result = array_slice( $array, $paginate['offset'], $page_limit );
	$time       = [];
	foreach ( $arr_result as $arr ) {
		$time       = get_between( $arr, '[', ']' );
		$time       = explode( ' ', $time );
		$error_name = get_between( $arr, "] ", ":  " );
		$error_msg  = get_from_char( $arr, ":  " );
		$i++;
		$bg                          = ( $i % 2 == 0 ? '#fff' : '#f5f5f5' );
		( isset( $time[1] ) ) ? $time[1] = $time[1] : $time[1] = null;
		( isset( $time[2] ) ) ? $time[2] = $time[2] : $time[2] = null;
		if ( null != $time[1] ) {
			?>
            <tr style="background-color:<?php echo $bg; ?>">
                <td><?=$time[0] . ' | ' . $time[1] . ' | ' . $time[2]?></td>
                <td><?=$error_name?></td>
                <td><?=$error_msg?></td>
            </tr>
            <?php
}
	}
	?>

        </tbody>
    </table>
    <style>
    .pagination {
        justify-content: flex-end;
    }
    </style>
    <div class="pagination" role="navigation" aria-label="pagination">
        <div class="tablenav-pages">
            <span class="pagination-links">
                <span class="displaying-num">
                    <?php _e( 'Total : ', 'wpsultan-debug' )?><?php echo $arr_count ?>
                </span>
                <a class="first-page button <?php echo ( $page <= 1 ? "disabled" : "" ); ?>"
                    href="<?php echo currentUrl() ?>&paged=1">
                    <span class="screen-reader-text"></span><span aria-hidden="true">
                        «
                    </span>
                </a>
                <a class="prev-page button <?php echo ( $page <= 1 ? "disabled" : "" ); ?>"
                    href="<?php if ( $page <= 1 ) {echo '#';} else {echo currentUrl() . "&paged=" . ( $page - 1 );}?>">
                    <span class="screen-reader-text"></span><span aria-hidden="true">
                        ‹
                    </span>
                </a>
                <span class="screen-reader-text">

                </span>
                <span id="table-paging" class="paging-input">
                    <span class="tablenav-paging-text">
                        <?php _e( 'Page ', 'wpsultan-debug' );?><?php echo $page; ?><?php _e( ' From', 'wpsultan-debug' )?>
                        <span class="total-pages"><?php echo $paginate['total'] ?>
                        </span>
                    </span>
                </span>
                <a class="next-page button <?php echo ( $page >= $paginate['total'] ? "disabled" : "" ); ?>"
                    href="<?php if ( $page >= $paginate['total'] ) {echo '#';} else {echo currentUrl() . "&paged=" . ( $page + 1 );}?>">
                    <span class="screen-reader-text"></span>
                    <span aria-hidden="true">
                        ›
                    </span>
                </a>
                <a class="last-page button <?php echo ( $page >= $paginate['total'] ? "disabled" : "" ); ?>"
                    href="<?php echo currentUrl() . "&paged=" . $paginate['total']; ?>">
                    <span class="screen-reader-text"></span><span aria-hidden="true">
                        »
                    </span>
                </a>
            </span>
        </div>
    </div>
</div>

<?php
}
