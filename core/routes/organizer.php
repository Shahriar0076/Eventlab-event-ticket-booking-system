<?php

use Illuminate\Support\Facades\Route;


Route::namespace('Organizer\Auth')->name('organizer.')->group(function () {

    Route::middleware('organizer.guest')->group(function () {
        Route::controller('LoginController')->group(function () {
            Route::get('/login', 'showLoginForm')->name('login');
            Route::post('/login', 'login');
            Route::get('logout', 'logout')->middleware('organizer')->withoutMiddleware('organizer.guest')->name('logout');
        });

        Route::controller('RegisterController')->middleware(['organizer.guest'])->group(function () {
            Route::get('register', 'showRegistrationForm')->name('register');
            Route::post('register', 'register');
            Route::post('check-user', 'checkUser')->name('checkUser')->withoutMiddleware('organizer.guest');
        });

        Route::controller('ForgotPasswordController')->prefix('password')->name('password.')->group(function () {
            Route::get('reset', 'showLinkRequestForm')->name('request');
            Route::post('email', 'sendResetCodeEmail')->name('email');
            Route::get('code-verify', 'codeVerify')->name('code.verify');
            Route::post('verify-code', 'verifyCode')->name('verify.code');
        });
        Route::controller('ResetPasswordController')->group(function () {
            Route::post('password/reset', 'reset')->name('password.update');
            Route::get('password/reset/{token}', 'showResetForm')->name('password.reset');
        });

        Route::controller('SocialiteController')->group(function () {
            Route::get('social-login/{provider}', 'socialLogin')->name('social.login');
            Route::get('social-login/callback/{provider}', 'callback')->name('social.login.callback');
        });
    });
});


Route::middleware('organizer')->name('organizer.')->group(function () {

    Route::get('organizer-data', 'Organizer\OrganizerController@organizerData')->name('data');
    Route::post('organizer-data-submit', 'Organizer\OrganizerController@organizerDataSubmit')->name('data.submit');

    //authorization
    Route::middleware('organizer.registration.complete')->namespace('Organizer')->controller('AuthorizationController')->group(function () {
        Route::get('authorization', 'authorizeForm')->name('authorization');
        Route::get('resend-verify/{type}', 'sendVerifyCode')->name('send.verify.code');
        Route::post('verify-email', 'emailVerification')->name('verify.email');
        Route::post('verify-mobile', 'mobileVerification')->name('verify.mobile');
        Route::post('verify-g2fa', 'g2faVerification')->name('2fa.verify');
    });

    Route::middleware(['organizer.check', 'organizer.registration.complete'])->group(function () {

        Route::namespace('Organizer')->group(function () {
            Route::controller('OrganizerController')->group(function () {
                Route::get('dashboard', 'home')->name('home');
                Route::get('download-attachments/{file_hash}', 'downloadAttachment')->name('download.attachment');

                //2FA
                Route::get('twofactor', 'show2faForm')->name('twofactor');
                Route::post('twofactor/enable', 'create2fa')->name('twofactor.enable');
                Route::post('twofactor/disable', 'disable2fa')->name('twofactor.disable');

                //KYC
                Route::get('kyc-form', 'kycForm')->name('kyc.form');
                Route::get('kyc-data', 'kycData')->name('kyc.data');
                Route::post('kyc-submit', 'kycSubmit')->name('kyc.submit');

                //Report
                Route::get('transactions', 'transactions')->name('transactions');
                Route::post('add-device-token', 'addDeviceToken')->name('add.device.token');
                Route::get('attachment-download/{file_hash}', 'attachmentDownload')->name('attachment.download');
            });

            //Manage Events
            Route::controller('EventController')->prefix('event')->name('event.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/draft', 'draft')->name('draft');
                Route::get('/approved', 'approved')->name('approved');
                Route::get('/pending', 'pending')->name('pending');
                Route::get('/rejected', 'rejected')->name('rejected');
                Route::get('/running', 'running')->name('running');
                Route::get('/upcoming', 'upcoming')->name('upcoming');


                Route::get('overview/{id?}', 'overview')->name('overview');
                Route::post('store-overview/{id?}', 'storeOverview')->name('store.overview');

                Route::get('info/{id?}', 'info')->name('info');
                Route::post('store-info/{id?}', 'storeInfo')->name('store.info');

                Route::get('time-slots/{id?}', 'timeSlots')->name('time.slots');
                Route::post('store-time-slots/{id?}', 'storeTimeSlots')->name('store.time.slots');

                Route::get('gallery/{id}', 'gallery')->name('gallery');
                Route::post('store-gallery/{id}', 'storeGallery')->name('store.gallery');

                Route::get('speakers/{id?}', 'speakers')->name('speakers');
                Route::post('store-speakers/{id?}', 'storeSpeakers')->name('store.speakers');

                Route::get('publish/{id?}', 'publish')->name('publish');
                Route::post('store-publish/{id?}', 'storePublish')->name('store.publish');

                Route::post('status/{id}', 'status')->name('status');

                Route::get('{id}/tickets', 'tickets')->name('ticket.index');
                Route::get('tickets/completed', 'completedOrders')->name('ticket.completed');
                Route::get('ticket-details/{ticketId}', 'ticketDetails')->name('ticket.details');
                Route::post('ticket-cancel/{ticketId}', 'cancelTicket')->name('ticket.cancel');
            });


            //Profile setting
            Route::controller('ProfileController')->group(function () {
                Route::get('profile-setting', 'profile')->name('profile.setting');
                Route::post('profile-setting', 'submitProfile');
                Route::get('change-password', 'changePassword')->name('change.password');
                Route::post('change-password', 'submitPassword');
            });


            // Withdraw
            Route::controller('WithdrawController')->prefix('withdraw')->name('withdraw')->group(function () {

                Route::middleware('kyc')->group(function () {
                    Route::get('/', 'withdrawMoney');
                    Route::post('/', 'withdrawStore')->name('.money');
                    Route::get('preview', 'withdrawPreview')->name('.preview');
                    Route::post('preview', 'withdrawSubmit')->name('.submit');
                });

                Route::get('history', 'withdrawLog')->name('.history');
                Route::get('approved', 'withdrawApproved')->name('.approved');
                Route::get('pending', 'withdrawPending')->name('.pending');
            });

            Route::controller('TicketController')->prefix('ticket')->group(function () {
                Route::get('all', 'supportTicket')->name('ticket.index');
                Route::get('new', 'openSupportTicket')->name('ticket.open');
                Route::post('create', 'storeSupportTicket')->name('ticket.store');
                Route::get('view/{ticket}', 'viewTicket')->name('ticket.view');
                Route::post('reply/{ticket}', 'replyTicket')->name('ticket.reply');
                Route::post('close/{ticket}', 'closeTicket')->name('ticket.close');
                Route::get('download/{ticket}', 'ticketDownload')->name('ticket.download');
            });
        });
    });
});
