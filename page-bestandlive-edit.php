<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */

get_header();

require get_template_directory() . '/inc/bestandlive-class.php';

function mx_redirect( $immediately ) { 

	if( $immediately ) { ?>
		<script>
			window.location.href = '/bestandlive-edit';
		</script>
	<?php } else { ?>
		<script>
			setTimeout( function() {
				window.location.href = '/bestandlive-edit';
			}, 3000 );			
		</script>
	<?php }

}
?>

<style>
	.mx-word-wrap {
		word-wrap: break-word
	}
	table {
		table-layout: fixed;
		width: 100%
	}
	.mx-remove-item {
		float: right;
		margin-bottom: 20px;
		margin-top: -42px;
	}
	.mx-besta-edit button {
		margin-top: 18px;
	}
</style>

<div>

	<h2><?php the_title(); ?></h2>

	<hr>

	<?php 

		global $wpdb;

		$bestandliveInstance = new MxBestandliveCRUD( $_GET, $_POST );
		// results
		$results = $bestandliveInstance->mx_get_results_b();

		if( $results ) :

			echo "<a href='?add_item=1' class='mx-add-item'>Add Item</a>";

			echo '<table>';

				echo '<tr>';

					echo '<th>product_id</th>';

					echo '<th>sorte</th>';

					echo '<th>kultivar_genetik</th>';

					echo '<th>thc_cbd</th>';

					echo '<th>firma</th>';

					echo '<th>verfuegbarkeit</th>';

					echo '<th width="300">infos</th>';

					echo '<th>produktcode</th>';

					echo '<th>stand</th>';

					echo '<th width="100">Actions</th>';

				echo '</tr>';

			foreach ( $results as $key => $value ) {

				echo '<tr>';

					echo '<td>';

						echo $value->product_id;

					echo '</td>';

					echo '<td>';

						echo $value->sorte;

					echo '</td>';

					echo '<td>';

						echo $value->kultivar_genetik;

					echo '</td>';

					echo '<td>';

						echo $value->thc_cbd;

					echo '</td>';

					echo '<td>';

						echo $value->firma;

					echo '</td>';

					echo '<td>';

						echo $value->verfuegbarkeit;

					echo '</td>';

					echo '<td>';

						echo '<div class="mx-word-wrap">';

							echo $value->infos;

						echo '</div>';

					echo '</td>';

					echo '<td>';

						echo $value->produktcode;

					echo '</td>';

					echo '<td>';

						echo $value->stand;

					echo '</td>';

					echo '<td>';

						echo '<a href="?edit=' . $value->product_id . '">Edit</a>';

					echo '</td>';
				
				echo '</tr>';

			}
			echo '</table>';

		endif;

		// edit
		$edit_item = $bestandliveInstance->mx_edit_item_b();

		if( $edit_item && $edit_item !== NULL ) :

			echo '<a href="/bestandlive-edit">Back to the list</a>';

			echo '<form method="POST" action="?edit=' . $_GET['edit'] . '" class="mx-besta-form mx-besta-edit">';

				echo '<ul>';

					foreach ( $edit_item as $key => $value ) {

						if( $key == 'created_on' ) continue;	

						if( $key == 'product_id' ) {

							echo "<input type='hidden' id='{$key}' name='{$key}' value='{$value}'>";

						} else {

							echo '<li>';	

							echo "<label for='{$key}'><b>{$key}</b></label>";

							echo "<input type='text' id='{$key}' name='{$key}' value='{$value}'>";

							echo '</li>';

						}

					}

				echo '</ul>';

				echo '<button type="submit">Save</button>';

			echo '</form>';

			// delete
			echo '<form method="POST" action="?delete=' . $_GET['edit'] . '" class="mx-remove-item mx-besta-form">';

				echo "<input type='hidden' id='product_id_del' name='product_id_del' value='{$_GET['edit']}'>";

				echo '<button type="submit" onclick="return confirm(\'are you sure?\')">Remove Item</button>';

			echo '</form>';


		endif;

		// save
		$save_data = $bestandliveInstance->mx_save_data_b();

		if( $save_data ) {

			$sorte 				= sanitize_text_field( $_POST['sorte'] );
			$kultivar_genetik 	= sanitize_text_field( $_POST['kultivar_genetik'] );
			$thc_cbd 			= sanitize_text_field( $_POST['thc_cbd'] );
			$firma 				= sanitize_text_field( $_POST['firma'] );
			$verfuegbarkeit 	= sanitize_text_field( $_POST['verfuegbarkeit'] );
			$infos 				= $_POST['infos'];
			$produktcode 		= sanitize_text_field( $_POST['produktcode'] );
			$stand 				= sanitize_text_field( $_POST['stand'] );

			$save = $wpdb->update( 'bestandlive',
				[
					'sorte' 			=> $sorte,
					'kultivar_genetik' 	=> $kultivar_genetik,
					'thc_cbd' 			=> $thc_cbd,
					'firma' 			=> $firma,
					'verfuegbarkeit' 	=> $verfuegbarkeit,
					'infos' 			=> $infos,
					'produktcode' 		=> $produktcode,
					'stand' 			=> $stand
				],
				[
					'product_id' => $_POST['product_id']
				]
			);

			if( $save == 1 ) {

				echo '<h1>Data saved!</h1>';

			} else {

				echo '<h1>Something went wrong!</h1>';

			}

			mx_redirect( false );

		}

		// add item
		$add_item = $bestandliveInstance->mx_add_item_b();

		if( $add_item ) { ?>

			<form method="POST" action="?save_item=1" class="mx-besta-form">
				<ul>

					<li><label for="sorte"><b>sorte</b></label><input type="text" id="sorte" name="sorte" value=""></li>

					<li><label for="kultivar_genetik"><b>kultivar_genetik</b></label><input type="text" id="kultivar_genetik" name="kultivar_genetik" value=""></li>

					<li><label for="thc_cbd"><b>thc_cbd</b></label><input type="text" id="thc_cbd" name="thc_cbd" value=""></li>

					<li><label for="firma"><b>firma</b></label><input type="text" id="firma" name="firma" value=""></li>

					<li><label for="verfuegbarkeit"><b>verfuegbarkeit</b></label><input type="text" id="verfuegbarkeit" name="verfuegbarkeit" value=""></li>

					<li><label for="infos"><b>infos</b></label><input type="text" id="infos" name="infos" value=""></li>

					<li><label for="produktcode"><b>produktcode</b></label><input type="text" id="produktcode" name="produktcode" value=""></li>

					<li><label for="stand"><b>stand</b></label><input type="text" id="stand" name="stand" value=""></li>
				</ul>

				<button type="submit">Add Item</button>
			</form>

		<?php }

		// save a new item
		$save_new_item_inst = $bestandliveInstance->mx_save_item_b();

		if( $save_new_item_inst ) {

			$sorte 				= sanitize_text_field( $_POST['sorte'] );
			$kultivar_genetik 	= sanitize_text_field( $_POST['kultivar_genetik'] );
			$thc_cbd 			= sanitize_text_field( $_POST['thc_cbd'] );
			$firma 				= sanitize_text_field( $_POST['firma'] );
			$verfuegbarkeit 	= sanitize_text_field( $_POST['verfuegbarkeit'] );
			$infos 				= $_POST['infos'];
			$produktcode 		= sanitize_text_field( $_POST['produktcode'] );
			$stand 				= sanitize_text_field( $_POST['stand'] );

			$save_new_item = $wpdb->insert(
				'bestandlive',
				[
					'sorte' 			=> $sorte,
					'kultivar_genetik' 	=> $kultivar_genetik,
					'thc_cbd' 			=> $thc_cbd,
					'firma' 			=> $firma,
					'verfuegbarkeit' 	=> $verfuegbarkeit,
					'infos' 			=> $infos,
					'produktcode' 		=> $produktcode,
					'stand' 			=> $stand
				],
				[
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s'
				]
			);

			if( $save_new_item == 1 ) {

				echo '<h1>New Item has created!</h1>';

			} else {

				echo '<h1>Something went wrong!</h1>';

			}

			mx_redirect( false );

		}

		// remove item
		$remove_item = $bestandliveInstance->mx_remove_item_b();

		if( $remove_item ) {

			if( $_GET['delete'] == $_POST['product_id_del'] ) {

				$delete_item = $wpdb->delete(
					'bestandlive', 
					[
						'product_id' => $_GET['delete']
					]
				);

				if( $delete_item == 1 ) {

					echo '<h1>Item has removed!</h1>';

				} else {

					echo '<h1>Something went wrong!</h1>';

				}

				mx_redirect( false );

			}			

		}

	?>

</div><!-- #site-content -->

<?php
get_footer();
