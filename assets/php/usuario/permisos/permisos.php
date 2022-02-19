<?php
echo '<div class="card">
		<div class="card-body">
			<div id="caja-permiso"></div>
		</div>
	</div>
	<select id="ano" class="sources">';
		$ano = date("Y");
		for ($i = 2022; $i <= 2025; $i++) { if ($ano==$i) { $select_ano="selected" ; } else { $select_ano="" ; }
			echo '<option ' . $select_ano . ' value="' . $i . '">' . $i . '</option>' ; } echo '</select>
					<select id="permiso" class="sources">
						<option value="0" selected="true">Permiso con goce de sueldo</option>
						<option value="1">Permiso sin goce de sueldo</option>
					</select>
	';