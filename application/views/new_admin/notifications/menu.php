<!--begin::Notifications-->
<div class="mx-5">
	<!--begin::Header-->
	<h3 class="fw-bold text-dark mb-10 mx-0"><?= Lang::__('Notifications')?></h3>
	<!--end::Header-->
	<!--begin::Body-->
	<div class="mb-12">
        <?foreach($notifications as $notification){?>
            <!--begin::Item-->
            <div class="d-flex align-items-center bg-light-warning rounded p-5 mb-7">
                <!--begin::Icon-->
                <span class="svg-icon text-warning me-5">
                    <i class="ki-duotone ki-<?= $notification["icon"]?> fs-1 text-<?= $notification["color"]?>">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </span>
                <!--end::Icon-->
                <!--begin::Title-->
                <div class="flex-grow-1 me-2">
                    <a href="<?= URL::base().$notification["url"]?>" class="fw-bold text-gray-800 text-hover-primary fs-6"><?= $notification["title"]?></a>
                    <span class="text-muted fw-semibold d-block"><?= $notification["date"] ?></span>
                </div>
                <!--end::Title-->
                <!--begin::Lable-->
                <span class="fw-bold text-warning py-1">+28%</span>
                <!--end::Lable-->
            </div>
            <!--end::Item-->
        <?}?>
		<!--begin::Item-->
		<div class="d-flex align-items-center bg-light-success rounded p-5 mb-7">
			<!--begin::Icon-->
			<span class="svg-icon text-success me-5">
				<i class="ki-duotone ki-file-added fs-1 text-success">
					<span class="path1"></span>
					<span class="path2"></span>
				</i>
			</span>
			<!--end::Icon-->
			<!--begin::Title-->
			<div class="flex-grow-1 me-2">
				<a href="../../demo7/dist/widgets/lists.html" class="fw-bold text-gray-800 text-hover-primary fs-6">Navigation optimization</a>
				<span class="text-muted fw-semibold d-block">Due in 2 Days</span>
			</div>
			<!--end::Title-->
			<!--begin::Lable-->
			<span class="fw-bold text-success py-1">+50%</span>
			<!--end::Lable-->
		</div>
		<!--end::Item-->
		<!--begin::Item-->
		<div class="d-flex align-items-center bg-light-danger rounded p-5 mb-7">
			<!--begin::Icon-->
			<span class="svg-icon text-danger me-5">
				<i class="ki-duotone ki-message-text-2 fs-1 text-danger">
					<span class="path1"></span>
					<span class="path2"></span>
					<span class="path3"></span>
				</i>
			</span>
			<!--end::Icon-->
			<!--begin::Title-->
			<div class="flex-grow-1 me-2">
				<a href="../../demo7/dist/widgets/lists.html" class="fw-bold text-gray-800 text-hover-primary fs-6">Humbert Bresnen</a>
				<span class="text-muted fw-semibold d-block">Due in 5 Days</span>
			</div>
			<!--end::Title-->
			<!--begin::Lable-->
			<span class="fw-bold text-danger py-1">-27%</span>
			<!--end::Lable-->
		</div>
		<!--end::Item-->
		<!--begin::Item-->
		<div class="d-flex align-items-center bg-light-info rounded p-5 mb-7">
			<!--begin::Icon-->
			<span class="svg-icon text-info me-5">
				<i class="ki-duotone ki-briefcase fs-1 text-info">
					<span class="path1"></span>
					<span class="path2"></span>
				</i>
			</span>
			<!--end::Icon-->
			<!--begin::Title-->
			<div class="flex-grow-1 me-2">
				<a href="../../demo7/dist/widgets/lists.html" class="fw-bold text-gray-800 text-hover-primary fs-6">Air B & B - Real Estate</a>
				<span class="text-muted fw-semibold d-block">Due in 8 Days</span>
			</div>
			<!--end::Title-->
			<!--begin::Lable-->
			<span class="fw-bold text-info py-1">+21%</span>
			<!--end::Lable-->
		</div>
		<!--end::Item-->
		<!--begin::Item-->
		<div class="d-flex align-items-center bg-light-primary rounded p-5 mb-7">
			<!--begin::Icon-->
			<span class="svg-icon text-primary me-5">
				<i class="ki-duotone ki-arrows-loop fs-1 text-primary">
					<span class="path1"></span>
					<span class="path2"></span>
				</i>
			</span>
			<!--end::Icon-->
			<!--begin::Title-->
			<div class="flex-grow-1 me-2">
				<a href="../../demo7/dist/widgets/lists.html" class="fw-bold text-gray-800 text-hover-primary fs-6">B & Q - Food Company</a>
				<span class="text-muted fw-semibold d-block">Due in 6 Days</span>
			</div>
			<!--end::Title-->
			<!--begin::Lable-->
			<span class="fw-bold text-primary py-1">+12%</span>
			<!--end::Lable-->
		</div>
		<!--end::Item-->
		<!--begin::Item-->
		<div class="d-flex align-items-center bg-light-danger rounded p-5">
			<!--begin::Icon-->
			<span class="svg-icon text-danger me-5">
				<i class="ki-duotone ki-pencil fs-1 text-danger">
					<span class="path1"></span>
					<span class="path2"></span>
				</i>
			</span>
			<!--end::Icon-->
			<!--begin::Title-->
			<div class="flex-grow-1 me-2">
				<a href="../../demo7/dist/widgets/lists.html" class="fw-bold text-gray-800 text-hover-primary fs-6">Nexa - Next generation</a>
				<span class="text-muted fw-semibold d-block">Due in 4 Days</span>
			</div>
			<!--end::Title-->
			<!--begin::Lable-->
			<span class="fw-bold text-danger py-1">+34%</span>
			<!--end::Lable-->
		</div>
		<!--end::Item-->
	</div>
	<!--end::Body-->
</div>