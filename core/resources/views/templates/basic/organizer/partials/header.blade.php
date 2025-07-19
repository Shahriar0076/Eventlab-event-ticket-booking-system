<div class="dashboard-header">
    <div class="dashboard-header__inner flex-between">
        <div class="dashboard-header__left">
            <div class="dashboard-body__bar d-lg-none d-block">
                <span class="dashboard-body__bar-icon"><i class="las la-bars"></i></span>
            </div>
        </div>
        <div class="dashboard-header__right flex-align">
            <div class="user-info">
                <div class="user-info__right">
                    <div class="user-info__thumb">
                        <img src="{{ getImage(getFilePath('organizerProfile') . '/' . authOrganizer()->profile_image, getFileSize('organizerCover'), $avatar = true) }}" alt="image">
                    </div>
                    <div class="user-info__profile">
                        <p class="user-info__name"> {{ @authOrganizer()->username }} </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('style')
    <style>
        .dashboard .user-info__right {
            gap: 10px;
        }
    </style>
@endpush
