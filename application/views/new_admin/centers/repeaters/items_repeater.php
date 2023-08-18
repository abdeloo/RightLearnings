<div data-repeater-item  style="<?php if (isset($Obj) && count($Items) > 0) { ?>display: none;<?php } ?>">
	<?php if(isset($Item)){ ?>
        <input type="text" name="id" value="<?php echo $Item->id; ?>" hidden="" />
    <?php } ?>
	<?if($type == "Stages" || $type == "Grades"){?>
		<div class="form-group row">
			<label class="required fs-6 fw-bold mb-2"><?= Lang::__($parent_name)?></label>
			<select class="form-select select2me form-select-solid" data-kt-repeater="select2" data-allow-clear="true" data-placeholder="<?= Lang::__('select')?>" name="<?= $parent_name ?>">
				<option></option>
				<?foreach($Parents as $Parent){?>
					<option value="<?= $Parent->id?>" <?php if(isset($Section) && $Section->teacher == $Teacher->id){ ?>selected=""<?php } ?>><?= $Parent->{'name_'.$lang}?></option>
				<?}?>
			</select>
		</div>
	<?}?>
	<div class="form-group row">
		<div class="col-md-5">
			<label class="form-label"><?= Lang::__('name_ar'); ?></label>
			<input type="text" name="name_ar" class="form-control mb-2 mb-md-0" value="<?php if(isset($Item)){echo $Item->name_ar;} ?>" placeholder="<?= Lang::__('name_ar'); ?>" />
		</div>
		<div class="col-md-5">
			<label class="form-label"><?= Lang::__('name_en'); ?>:</label>
			<input type="text" name="name_en" class="form-control mb-2 mb-md-0" placeholder="<?= Lang::__('name_en');  ?>" value="<?php if(isset($Item)){echo $Item->name_en;} ?>" />
		</div>
		<div class="col-md-2">
			<a href="javascript:;" par1="<?php if(isset($Item)){ echo $Item->id;} ?>" data-repeater-delete class="btn btn-sm btn-light-danger mt-3 mt-md-8">
				<i class="la la-trash-o"></i><?= Lang::__('delete'); ?>
			</a>
		</div>
		<div class="col-md-6">
			<label class="form-label"><?= Lang::__('desc_ar')?>:</label>
			<textarea class="form-control form-control-solid" rows="3" name="desc_ar" placeholder="<?= Lang::__('desc_ar')?>"><?php if (isset($Item)) { echo $Item->desc_ar; } ?></textarea>
		</div>
		<div class="col-md-6">
			<label class="form-label"><?= Lang::__('desc_en')?>:</label>
			<textarea class="form-control form-control-solid" rows="3" name="desc_en" placeholder="<?= Lang::__('desc_en')?>"><?php if (isset($Item)) { echo $Item->desc_en; } ?></textarea>
		</div>                                              
	</div>
	<?if($type == "Grades"){?>
		<div class="form-group row">
			<label class="required fs-6 fw-bold mb-2"><?= Lang::__("Courses")?></label>
			<select class="form-select select2me form-select-solid" data-kt-repeater="select2" data-allow-clear="true" data-placeholder="<?= Lang::__('select')?>" name="Courses_in_all[]" multiple>
				<option></option>
				<?foreach($Courses as $Course){?>
					<option value="<?= $Course->id?>" <?php if(isset($Section) && $Section->teacher == $Course->id){ ?>selected=""<?php } ?>><?= $Course->{'name_'.$lang}?></option>
				<?}?>
			</select>
		</div>
	<?}?>
	<br><br>
</div>