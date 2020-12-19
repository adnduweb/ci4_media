<div class="row">
			<div class="col-md-9">
				<div class="mb-3 image_container">
					<img class="image-upload" id="image-upload" data-input="<?= $input; ?>" data-only="<?= $only; ?>" data-crop_width="<?= $crop_width; ?>" data-crop_height="<?= $crop_height; ?>" data-field="<?= $field; ?>" data-extension="<?= $extension; ?>" data-mine="<?= $mine; ?>" data-uuid="<?= $uuid; ?>" src="<?= img_data($media->getOriginal()); ?>" alt="">
				</div>


				<div id="cropper-buttons">
					<?php if ($crop_width == false && $crop_height == false) { ?>
						<div class="btn-group">
							<button type="button" class="btn btn-primary mb-3" data-method="setDragMode" data-option="move" title="Move">
								<span class="" data-toggle="kt-tooltip" title="cropper.setDragMode(&quot;move&quot;)">
									<span class="fa fa-arrows-alt"></span>
								</span>
							</button>
							<button type="button" class="btn btn-primary mb-3" data-method="setDragMode" data-option="crop" title="Crop">
								<span class="" data-toggle="kt-tooltip" title="cropper.setDragMode(&quot;crop&quot;)" aria-describedby="tooltip15929">
									<span class="fa fa-crop-alt"></span>
								</span>
							</button>
						</div>

						<div class="btn-group">
							<button type="button" class="btn btn-primary mb-3" data-method="zoom" data-option="0.1" title="Zoom In">
								<span class="" data-toggle="kt-tooltip" title="cropper.zoom(0.1)">
									<span class="fa fa-search-plus"></span>
								</span>
							</button>
							<button type="button" class="btn btn-primary mb-3" data-method="zoom" data-option="-0.1" title="Zoom Out">
								<span class="" data-toggle="kt-tooltip" title="cropper.zoom(-0.1)">
									<span class="fa fa-search-minus"></span>
								</span>
							</button>
						</div>

						<div class="btn-group">
							<button type="button" class="btn btn-primary mb-3" data-method="move" data-option="-10" data-second-option="0" title="Move Left">
								<span class="" data-toggle="kt-tooltip" title="cropper.move(-10, 0)">
									<span class="fa fa-arrow-left"></span>
								</span>
							</button>
							<button type="button" class="btn btn-primary mb-3" data-method="move" data-option="10" data-second-option="0" title="Move Right">
								<span class="" data-toggle="kt-tooltip" title="cropper.move(10, 0)">
									<span class="fa fa-arrow-right"></span>
								</span>
							</button>
							<button type="button" class="btn btn-primary mb-3" data-method="move" data-option="0" data-second-option="-10" title="Move Up">
								<span class="" data-toggle="kt-tooltip" title="cropper.move(0, -10)">
									<span class="fa fa-arrow-up"></span>
								</span>
							</button>
							<button type="button" class="btn btn-primary mb-3" data-method="move" data-option="0" data-second-option="10" title="Move Down">
								<span class="" data-toggle="kt-tooltip" title="cropper.move(0, 10)">
									<span class="fa fa-arrow-down"></span>
								</span>
							</button>
						</div>

						<div class="btn-group">
							<button type="button" class="btn btn-primary mb-3" data-method="rotate" data-option="-45" title="Rotate Left">
								<span class="" data-toggle="kt-tooltip" title="cropper.rotate(-45)">
									<span class="fa fa-undo-alt"></span>
								</span>
							</button>
							<button type="button" class="btn btn-primary mb-3" data-method="rotate" data-option="45" title="Rotate Right">
								<span class="" data-toggle="kt-tooltip" title="cropper.rotate(45)">
									<span class="fa fa-redo-alt"></span>
								</span>
							</button>
						</div>

						<div class="btn-group">
							<button type="button" class="btn btn-primary mb-3" data-method="scaleX" data-option="-1" title="Flip Horizontal">
								<span class="" data-toggle="kt-tooltip" title="cropper.scaleX(-1)">
									<span class="fa fa-arrows-alt-h"></span>
								</span>
							</button>
							<button type="button" class="btn btn-primary mb-3" data-method="scaleY" data-option="-1" title="Flip Vertical">
								<span class="" data-toggle="kt-tooltip" title="cropper.scaleY(-1)">
									<span class="fa fa-arrows-alt-v"></span>
								</span>
							</button>
						</div>

						<div class="btn-group">
							<button type="button" class="btn btn-primary mb-3" data-method="crop" title="Crop">
								<span class="" data-toggle="kt-tooltip" title="cropper.crop()">
									<span class="fa fa-check"></span>
								</span>
							</button>
							<button type="button" class="btn btn-primary mb-3" data-method="clear" title="Clear">
								<span class="" data-toggle="kt-tooltip" title="cropper.clear()">
									<span class="fa fa-times"></span>
								</span>
							</button>
						</div>


						<div class="btn-group">
							<button type="button" class="btn btn-primary mb-3" data-method="reset" title="Reset">
								<span class="" data-toggle="kt-tooltip" title="cropper.reset()">
									<span class="fa fa-sync-alt"></span>
								</span>
							</button>

						</div>

						<div class="btn-group btn-group-crop">
							<button type="button" class="btn btn-success mb-3" data-method="getCroppedCanvas" data-option="">
								Cropper votre image
							</button>
						</div>
						<div class="btn-group btn-group-crop-cancel">
							<button type="reset" class="btn btn-dark mb-3 cancelCrop" data-field="<?= $field; ?>">
								Annuler
							</button>
						</div>
						<div class="btn-group d-flex flex-nowrap">
							<button type="button" class="btn btn-success" data-method="getCroppedCanvas" data-option="{ &quot;width&quot;: 1920, &quot;height&quot;: 1080 }">
								<span class="docs-tooltip" data-toggle="kt-tooltip" title="" data-original-title="cropper.getCroppedCanvas({ width: 1920, height: 1080 })">
									1920×1080
								</span>
							</button>
							<button type="button" class="btn btn-success" data-method="getCroppedCanvas" data-option="{ &quot;width&quot;: 800, &quot;height&quot;: 600 }">
								<span class="docs-tooltip" data-toggle="kt-tooltip" data-placement="top" title="" data-original-title="cropper.getCroppedCanvas({ width: 800, height: 600 })">
									800x600
								</span>
							</button>
						</div>

						<div class="btn-group d-flex flex-nowrap" data-toggle="buttons" id="setAspectRatio">
							<label class="btn btn-primary active">
								<input type="radio" class="sr-only" id="aspectRatio1" name="aspectRatio" value="1.7777777777777777">
								<span class="docs-tooltip" data-toggle="kt-tooltip" title="Ratio: 16 / 9">
									16:9
								</span>
							</label>
							<label class="btn btn-primary">
								<input type="radio" class="sr-only" id="aspectRatio2" name="aspectRatio" value="1.3333333333333333">
								<span class="docs-tooltip" data-toggle="kt-tooltip" title="Ratio: 4 / 3">
									4:3
								</span>
							</label>
							<label class="btn btn-primary">
								<input type="radio" class="sr-only" id="aspectRatio3" name="aspectRatio" value="1">
								<span class="docs-tooltip" data-toggle="kt-tooltip" title="Ratio: 1 / 1">
									1:1
								</span>
							</label>
							<label class="btn btn-primary">
								<input type="radio" class="sr-only" id="aspectRatio4" name="aspectRatio" value="0.6666666666666666">
								<span class="docs-tooltip" data-toggle="kt-tooltip" title="Ratio: 2 / 3">
									2:3
								</span>
							</label>
							<label class="btn btn-primary">
								<input type="radio" class="sr-only" id="aspectRatio5" name="aspectRatio" value="NaN">
								<span class="docs-tooltip" data-toggle="kt-tooltip" title="Ratio: Libre">
									Free
								</span>
							</label>
						</div>


						<textarea class="form-control" id="putData" placeholder="Get data to here or set data with this value"></textarea>
					<?php } else { ?>
						<div class="btn-group">
							<button type="button" class="btn btn-primary mb-3" data-method="reset" title="Reset">
								<span class="" data-toggle="kt-tooltip" title="cropper.reset()">
									<span class="fa fa-sync-alt"></span>
								</span>
							</button>

						</div>
						<div class="btn-group btn-group-crop">
							<button type="button" class="btn btn-success mb-3" data-method="getCroppedCanvas" data-option="{ &quot;width&quot;: <?= $crop_width; ?>, &quot;height&quot;: <?= $crop_height; ?> }">
								<span class="docs-tooltip" data-toggle="kt-tooltip" title="" data-original-title="cropper.getCroppedCanvas({ width: <?= $crop_width; ?>, height: <?= $crop_height; ?> })">
									<?= $crop_width; ?>×<?= $crop_height; ?>
								</span>
							</button>
						</div>
						<div class="btn-group btn-group-crop-cancel">
							<button type="reset" class="btn btn-dark mb-3 cancelCrop" data-field="<?= $field; ?>">
								Annuler
							</button>
						</div>
						<div style="display:none !important" class="btn-group d-flex flex-nowrap" data-toggle="buttons" id="setAspectRatio">
							<label class="btn btn-primary active">
								<input type="radio" class="sr-only" id="aspectRatio1" name="aspectRatio" value="1.7777777777777777">
								<span class="docs-tooltip" data-toggle="kt-tooltip" title="Ratio: 16 / 9">
									16:9
								</span>
							</label>
							<label class="btn btn-primary">
								<input type="radio" class="sr-only" id="aspectRatio2" name="aspectRatio" value="1.3333333333333333">
								<span class="docs-tooltip" data-toggle="kt-tooltip" title="Ratio: 4 / 3">
									4:3
								</span>
							</label>
							<label class="btn btn-primary">
								<input type="radio" class="sr-only" id="aspectRatio3" name="aspectRatio" value="1">
								<span class="docs-tooltip" data-toggle="kt-tooltip" title="Ratio: 1 / 1">
									1:1
								</span>
							</label>
							<label class="btn btn-primary">
								<input type="radio" class="sr-only" id="aspectRatio4" name="aspectRatio" value="0.6666666666666666">
								<span class="docs-tooltip" data-toggle="kt-tooltip" title="Ratio: 2 / 3">
									2:3
								</span>
							</label>
							<label class="btn btn-primary">
								<input type="radio" class="sr-only" id="aspectRatio5" name="aspectRatio" value="NaN">
								<span class="docs-tooltip" data-toggle="kt-tooltip" title="Ratio: Libre">
									Free
								</span>
							</label>
						</div>
					<?php } ?>

				</div>
			</div>

			<div class="col-md-3 <?= ($only == true) ? 'only' : 'not-only'; ?>">

				<div class="cropper-preview clearfix mb-3">
					<div id="cropper-preview-lg" class="img-preview preview-lg img-fluid mb-3" style="width: 256px; height: 160px; overflow: hidden; background-color: #f7f7f7;"></div>
					<div id="cropper-preview-md" class="img-preview preview-md float-left" style="width: 128px; height: 80px; overflow: hidden; background-color: #f7f7f7;"></div>
					<div id="cropper-preview-sm" class="img-preview preview-sm float-left ml-3" style="width: 64px; height: 40px; overflow: hidden; background-color: #f7f7f7;"></div>
					<div id="cropper-preview-xs" class="img-preview preview-xs float-left ml-3" style="width: 32px; height: 20px; overflow: hidden; background-color: #f7f7f7;"></div>
				</div>
				<!-- <h3>Data:</h3> -->
				<div id="cropper-data">
					<div class="form-group">
						<div class="input-group">
							<div class="input-group-prepend">
								<label class="input-group-text" for="dataX">X</label>
							</div>
							<input type="text" class="form-control" id="dataX" placeholder="x">
							<div class="input-group-append">
								<span class="input-group-text">px</span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="input-group">
							<div class="input-group-prepend">
								<label class="input-group-text" for="dataY">Y</label>
							</div>
							<input type="text" class="form-control" id="dataY" placeholder="y">
							<div class="input-group-append">
								<span class="input-group-text">px</span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="input-group">
							<div class="input-group-prepend">
								<label class="input-group-text" for="dataWidth">Width</label>
							</div>
							<input type="text" class="form-control" id="dataWidth" placeholder="width">
							<div class="input-group-append">
								<span class="input-group-text">px</span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="input-group">
							<div class="input-group-prepend">
								<label class="input-group-text" for="dataHeight">Height</label>
							</div>
							<input type="text" class="form-control" id="dataHeight" placeholder="height">
							<div class="input-group-append">
								<span class="input-group-text">px</span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="input-group">
							<div class="input-group-prepend">
								<label class="input-group-text" for="dataRotate">Rotate</label>
							</div>
							<input type="text" class="form-control" id="dataRotate" placeholder="rotate">
							<div class="input-group-append">
								<span class="input-group-text">deg</span>
							</div>
						</div>
					</div>

					<div class="form-group">
						<div class="input-group">
							<div class="input-group-prepend">
								<label class="input-group-text" for="dataScaleX">ScaleX</label>
							</div>
							<input type="text" class="form-control" id="dataScaleX" placeholder="scaleX">
						</div>
					</div>
					<div class="form-group">
						<div class="input-group">
							<div class="input-group-prepend">
								<label class="input-group-text" for="dataScaleY">ScaleY</label>
							</div>
							<input type="text" class="form-control" id="dataScaleY" placeholder="scaleY">
						</div>
					</div>
				</div>

			</div>
		</div>