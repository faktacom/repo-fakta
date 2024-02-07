<?php

use App\Models\Admin\ListMaintenance;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

if (ListMaintenance::checkMaintenance()) {
    Route::get('/', 'Admin\MaintenanceController@isMaintenance')->name('welcome');
} else {
    Auth::routes();
    Route::get('/', 'FrontController@index')->name('welcome');
    Route::get('/video', 'FrontController@viewVideoAll')->name('video');
    Route::get('/webinar', 'FrontController@viewWebinarAll')->name('webinar');
    Route::get('/webinar/{slug}', 'FrontController@viewWebinarDetail')->name('webinar.detail');
    Route::post('/webinar-join', 'FrontController@processWebinarJoin')->name('webinar.join');
    Route::get('/survey', 'FrontController@viewSurveyAll')->name('survey');
    Route::get('/survey/{survey_code}', 'FrontController@viewSurveyDetail')->name('survey.detail');
    Route::get('/survey/{survey_code}/finish', 'FrontController@viewSurveyFinish')->name('survey.finish');
    Route::post('/survey-answer', 'FrontController@processSurveyAnswer')->name('survey.answer');
    Route::post('/survey-city', 'FrontController@processGetSurveyCity')->name('survey.city');
    Route::get('/detail/{user}/{slug}', 'FrontController@viewNewsDetail')->name('news.detail.old');
    Route::get('/news/{category_slug}/{slug}', 'FrontController@viewNewsDetail')->name('news.detail');
    Route::get('/preview/{user}/{slug}', 'FrontController@viewNewsPreview')->name('news.preview');
    Route::get('/trending', 'FrontController@viewTrendingDetail')->name('trending.detail');
    Route::get('/latest', 'FrontController@viewLatestDetail')->name('latest.detail');
    Route::get('/trending/{slug}', 'FrontController@viewTrendingCategory')->name('trendingCat.detail');


    Route::get('/footer/{slug}', 'FrontController@viewFooterMenu')->name('footer.detail');
    Route::post('/newsletter', 'FrontController@processNewsletter')->name('newsletter');
    Route::get('/maintenance', 'FrontController@viewMaintenance')->name('maintenance');

    // Category
    Route::get('/category', 'FrontController@viewCategoryAll')->name('category');
    Route::get('/category/{slug}', 'FrontController@viewCategoryDetail')->name('category.detail');
    Route::get('/tag/{slug}', 'FrontController@viewTagDetail')->name('tag.detail');
    Route::get('/author/{username}', 'FrontController@viewAuthorNewsDetail')->name('author.news');
    Route::get('/search', 'FrontController@processSearchNews')->name('front.search');
    Route::middleware('auth')->group(function () {
        Route::get('/profile/edit', 'UserController@viewProfileEdit')->name('profile.user');
        Route::get('/profile/settings', 'UserController@viewProfileSettingsDetail')->name('profile.settings');
        Route::get('/profile/settings/password', 'UserController@viewProfilePasswordEdit')->name('profile.password');
        Route::post('/profile/settings/password/{id}', 'UserController@processProfilePasswordEdit')->name('profile.passwordUpdate');

        Route::post('/profile/edit/{id}', 'UserController@processProfileEdit')->name('profile.user.update');

        Route::post('/profile/create-news/ckeditor', 'UserController@uploadCkEditor')->name('profile.ckeditor.upload');

        Route::get('/profile/create-news', 'UserController@viewNewsAdd')->name('profile.createNews');

        Route::get('/profile/my-content', 'UserController@viewContentList')->name('profile.myContent');

        Route::post('/profile/create-news/tambah', 'UserController@processNewsAdd')->name('profile.createNewsStore');

        Route::get('/profile/content/{slug}/edit', 'UserController@viewNewsEdit')->name('profile.editNews');

        Route::post('/profile/update-news/{id}', 'UserController@processNewsEdit')->name('profile.updateNews');

        Route::delete('/profile/delete', 'UserController@processNewsDelete')->name('profile.deleteNews');
    });

    Route::get('/{username?}', 'UserController@viewProfileDetail')->name('profile.detail');

    Route::post('/tambah-komentar', 'FrontController@processCommentAdd')->name('addComment.store');
    Route::post('/get-more-comment', 'FrontController@getMoreComments')->name('getMoreComment');
}


// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/ads-click', 'FrontController@isClickAds')->name('ads.click');
Route::post('/ads-view', 'FrontController@isViewAds')->name('ads.view');


// Admin
Route::group(['prefix' => 'admin'], function () {
    Route::get('/login', 'Auth\LoginController@showAdminLoginForm')->name('admin.login');
    Route::post('/', 'Auth\LoginController@login');
    Route::post('logout', 'Auth\LoginController@logout')->name('logout');
    Route::group(['middleware' => ['check_role:1|2|3|5|6|7|8']], function () {
        Route::get('/home', 'HomeController@index')->name('admin.home');
        Route::post('/home/filter', 'HomeController@filter')->name('admin.home.filter');
        Route::get('/profile', 'HomeController@viewProfileAdminEdit')->name('admin.profile');
        Route::post('/profile/{id}/update', 'HomeController@processProfileAdminEdit')->name('admin.profileUpdate');

        // Category
        Route::prefix('categories')->group(function () {
            Route::get('/', 'Admin\CategoryController@index')->name('admin.category.index');
            Route::post('/store', 'Admin\CategoryController@processCategoryAdd')->name('admin.category.store');
            Route::post('/update/{id}', 'Admin\CategoryController@processCategoryEdit')->name('admin.category.update');
            Route::post('/destroy/{id}', 'Admin\CategoryController@processCategoryDelete')->name('admin.category.destroy');
        });

        // Tag
        Route::prefix('tags')->group(function () {
            Route::get('/', 'Admin\TagController@index')->name('admin.tag.index');
            Route::post('/store', 'Admin\TagController@processTagAdd')->name('admin.tag.store');
            Route::post('/update/{id}', 'Admin\TagController@processTagEdit')->name('admin.tag.update');
            Route::post('/destroy/{id}', 'Admin\TagController@processTagDelete')->name('admin.tag.destroy');
        });

        // News
        Route::prefix('history-news')->group(function () {
            Route::get('/{id}', 'Admin\HistoryNewsController@index')->name('admin.historyNews.index');
            Route::get('/detail/{id}', 'Admin\HistoryNewsController@viewHistoryNewsDetail')->name('admin.historyNews.show');
        });

        // Webinar
        Route::prefix('webinar')->group(function () {
            Route::get('/', 'Admin\WebinarController@index')->name('admin.webinar.index');
            Route::get('/create', 'Admin\WebinarController@viewWebinarAdd')->name('admin.webinar.create');
            Route::post('/store', 'Admin\WebinarController@processWebinarAdd')->name('admin.webinar.store');
            Route::get('/detail/{id}', 'Admin\WebinarController@viewWebinarDetail')->name('admin.webinar.detail');
            Route::get('/edit/{id}', 'Admin\WebinarController@viewWebinarEdit')->name('admin.webinar.edit');
            Route::post('/update/{id}', 'Admin\WebinarController@processWebinarEdit')->name('admin.webinar.update');
            Route::post('/destroy/{id}', 'Admin\WebinarController@processWebinarDelete')->name('admin.webinar.destroy');
            Route::get('/participant/{id}', 'Admin\WebinarController@viewWebinarParticipant')->name('admin.webinar.participant');
            Route::get('/participant/download/{id}', 'Admin\WebinarController@downloadWebinarParticipant')->name('admin.webinar.download');
        });

        // Community
        Route::prefix('community')->group(function () {
            Route::get('/', 'Admin\CommunityController@index')->name('admin.community.index');
            Route::get('/create', 'Admin\CommunityController@viewCommunityAdd')->name('admin.community.create');
            Route::post('/store', 'Admin\CommunityController@processCommunityAdd')->name('admin.community.store');
            Route::get('/detail/{id}', 'Admin\CommunityController@viewCommunityDetail')->name('admin.community.detail');
            Route::get('/edit/{id}', 'Admin\CommunityController@viewCommunityEdit')->name('admin.community.edit');
            Route::post('/update/{id}', 'Admin\CommunityController@processCommunityEdit')->name('admin.community.update');
            Route::post('/destroy/{id}', 'Admin\CommunityController@processCommunityDelete')->name('admin.community.destroy');
            Route::get('/participant/export/{id}', 'Admin\WebinarController@exportWebinarParticipant')->name('admin.webinar.export');
        });

        Route::prefix('user')->group(function () {
            Route::get('/', 'Admin\UserController@index')->name('admin.user.index');
            Route::get('/kpi', 'Admin\UserController@viewUserPerformance')->name('admin.user.perform');
            Route::get('/kpi/{id}', 'Admin\UserController@viewUserPerformanceDetail')->name('admin.user.performDetail');
            Route::get('/uac', 'Admin\UserController@viewUserAccessControl')->name('admin.uac');
            Route::get('/uac/edit/{id}', 'Admin\UserController@viewUserAccessControlEdit')->name('admin.uac.edit');
            Route::post('/uac/update', 'Admin\UserController@processUserAccessControlEdit')->name('admin.uac.update');
            Route::get('/create', 'Admin\UserController@viewUserAdd')->name('admin.user.create');
            Route::post('/store', 'Admin\UserController@processUserAdd')->name('admin.user.store');
            Route::get('/detail/{id}', 'Admin\UserController@viewUserDetail')->name('admin.user.detail');
            Route::get('/edit/{id}', 'Admin\UserController@viewUserEdit')->name('admin.user.edit');
            Route::post('/update/{id}', 'Admin\UserController@processUserEdit')->name('admin.user.update');
            Route::post('/destroy/{id}', 'Admin\UserController@processUserDelete')->name('admin.user.destroy');
        });

        Route::prefix('video')->group(function () {
            Route::get('/', 'Admin\VideoController@index')->name('admin.video.index');
            Route::post('/store', 'Admin\VideoController@processVideoAdd')->name('admin.video.store');
            Route::post('/{id}/update', 'Admin\VideoController@processVideoEdit')->name('admin.video.update');
            Route::post('/{id}/destroy', 'Admin\VideoController@processVideoDelete')->name('admin.video.destroy');
        });

        Route::prefix('image')->group(function () {
            Route::get('/', 'Admin\ImageController@index')->name('admin.image.index');
            Route::post('/store', 'Admin\ImageController@processImageAdd')->name('admin.image.store');
            Route::post('/{id}/update', 'Admin\ImageController@processImageEdit')->name('admin.image.update');
            Route::post('/{id}/destroy', 'Admin\ImageController@processImageDelete')->name('admin.image.destroy');
        });

        Route::prefix('comment')->group(function () {
            Route::get('/', 'Admin\CommentController@index')->name('admin.comment.index');
            Route::post('/{id}/destroy', 'Admin\CommentController@processCommentDelete')->name('admin.comment.destroy');
        });

        Route::prefix('ads')->group(function () {
            Route::get('/', 'Admin\AdsController@index')->name('admin.ads.index');
            Route::post('/store', 'Admin\AdsController@processAdsAdd')->name('admin.ads.store');
            Route::post('/{id}/update', 'Admin\AdsController@processAdsEdit')->name('admin.ads.update');
            Route::post('/{id}/destroy', 'Admin\AdsController@processAdsDelete')->name('admin.ads.destroy');
        });

        Route::prefix('nama-data')->group(function () {
            Route::get('/', 'Admin\NamaDataController@index')->name('admin.namaData.index');
            Route::post('/store', 'Admin\NamaDataController@processNamaDataAdd')->name('admin.namaData.store');
            Route::post('/{id}/update', 'Admin\NamaDataController@processNamaDataEdit')->name('admin.namaData.update');
            Route::post('/{id}/destroy', 'Admin\NamaDataController@processNamaDataDelete')->name('admin.namaData.destroy');
        });

        Route::prefix('footer')->group(function () {
            Route::get('/', 'Admin\FooterLinkController@index')->name('admin.footer.index');
            Route::get('/edit/{id}', 'Admin\FooterLinkController@viewFooterLinkEdit')->name('admin.footer.edit');
            Route::get('/create', 'Admin\FooterLinkController@viewFooterLinkAdd')->name('admin.footer.create');
            Route::post('/store', 'Admin\FooterLinkController@processFooterLinkAdd')->name('admin.footer.store');
            Route::post('/update/{id}', 'Admin\FooterLinkController@processFooterLinkEdit')->name('admin.footer.update');
            Route::post('/destroy/{id}', 'Admin\FooterLinkController@processFooterLinkDelete')->name('admin.footer.destroy');
        });

        Route::prefix('terms-of-service')->group(function () {
            Route::get('/', 'Admin\TermsofServiceController@index')->name('admin.termsOfService.index');
            Route::get('/detail/{id}', 'Admin\TermsofServiceController@viewTermsofServiceDetail')->name('admin.termsOfService.show');
        });

        Route::prefix('privacy-policy')->group(function () {
            Route::get('/', 'Admin\PrivacyPolicyController@index')->name('admin.privacyPolicy.index');
            Route::get('/detail/{id}', 'Admin\PrivacyPolicyController@viewPrivacyPolicyDetail')->name('admin.privacyPolicy.show');
        });

        Route::prefix('maintenance')->group(function () {
            Route::get('/', 'Admin\MaintenanceController@index')->name('admin.maintenance.index');
            Route::post('/store', 'Admin\MaintenanceController@processMaintenanceAdd')->name('admin.maintenance.store');
            Route::post('/{id}/update', 'Admin\MaintenanceController@processMaintenanceEdit')->name('admin.maintenance.update');
            Route::post('/{id}/destroy', 'Admin\MaintenanceController@processMaintenanceDelete')->name('admin.maintenance.destroy');
        });

        Route::prefix('news')->group(function () {
            Route::get('/', 'Admin\NewsController@index')->name('admin.news.index');
            Route::get('/create', 'Admin\NewsController@viewNewsAdd')->name('admin.news.create');
            Route::get('/edit/{id}', 'Admin\NewsController@viewNewsEdit')->name('admin.news.edit');
            Route::post('/store', 'Admin\NewsController@processNewsAdd')->name('admin.news.store');
            Route::post('/update/{id}', 'Admin\NewsController@processNewsEdit')->name('admin.news.update');
            Route::post('/destroy/{id}', 'Admin\NewsController@processNewsDelete')->name('admin.news.destroy');
            Route::post('/update-status', 'Admin\NewsController@processNewsStatusEdit')->name('admin.news.updateStatus');
            Route::post('/ckeditor', 'Admin\NewsController@uploadCkEditor')->name('admin.ckeditor.upload');
            Route::post('/editorjs', 'Admin\NewsController@uploadEditorJs')->name('admin.editorjs.upload');
            Route::get('/editorjs-link', 'Admin\NewsController@linkEditorJs')->name('admin.editorjs.link');
        });
        
        Route::get('/all-news', 'Admin\NewsController@viewAllNews')->name('admin.news.all');

        Route::prefix('headline')->group(function () {
            Route::get('/', 'Admin\HeadlineController@index')->name('admin.headline.index');
            Route::get('/detail/{id}', 'Admin\HeadlineController@viewHeadlineDetail')->name('admin.headline.show');
            Route::get('/create', 'Admin\HeadlineController@viewHeadlineAdd')->name('admin.headline.create');
            Route::get('/edit/{id}', 'Admin\HeadlineController@viewHeadlineEdit')->name('admin.headline.edit');
            Route::post('/store', 'Admin\HeadlineController@processHeadlineAdd')->name('admin.headline.store');
            Route::get('/search', 'Admin\HeadlineController@processHeadlineSearch')->name('admin.headline.search');
            Route::get('/refresh', 'Admin\HeadlineController@processHeadlineRefresh')->name('admin.headline.refresh');
            Route::post('/update', 'Admin\HeadlineController@processHeadlineEdit')->name('admin.headline.update');
            Route::post('/destroy/{id}', 'Admin\HeadlineController@processHeadlineDelete')->name('admin.headline.destroy');
        });

        Route::prefix('bank')->group(function () {
            Route::get('/', 'Admin\BankController@index')->name('admin.bank.index');
            Route::get('/create', 'Admin\BankController@viewBankAdd')->name('admin.bank.create');
            Route::post('/store', 'Admin\BankController@processBankAdd')->name('admin.bank.store');
            Route::get('/detail/{id}', 'Admin\BankController@viewBankDetail')->name('admin.bank.detail');
            Route::get('/edit/{id}', 'Admin\BankController@viewBankEdit')->name('admin.bank.edit');
            Route::post('/update/{id}', 'Admin\BankController@processBankEdit')->name('admin.bank.update');
            Route::post('/destroy/{id}', 'Admin\BankController@processBankDelete')->name('admin.bank.destroy');
            Route::get('/search/{imageName}', 'Admin\BankController@processBankSearch')->name('admin.bank.search');
        });

        Route::prefix('survey')->group(function () {
            Route::get('/', 'Admin\SurveyController@index')->name('admin.survey.index');
            Route::get('/create', 'Admin\SurveyController@viewSurveyAdd')->name('admin.survey.create');
            Route::post('/store', 'Admin\SurveyController@processSurveyAdd')->name('admin.survey.store');
            Route::get('/detail/{id}', 'Admin\SurveyController@viewSurveyDetail')->name('admin.survey.detail');
            Route::get('/respond/{id}', 'Admin\SurveyController@viewSurveyRespond')->name('admin.survey.respond');
            Route::get('/respond-print/{id}', 'Admin\SurveyController@printSurveyRespond')->name('admin.survey.respond.print');
            Route::get('/allanswer/{id}', 'Admin\SurveyController@viewSurveyAllAnswer')->name('admin.survey.allanswer');
            Route::get('/answer/{id}', 'Admin\SurveyController@viewSurveyAnswer')->name('admin.survey.answer');
            Route::get('/edit/{id}', 'Admin\SurveyController@viewSurveyEdit')->name('admin.survey.edit');
            Route::post('/update/{id}', 'Admin\SurveyController@processSurveyEdit')->name('admin.survey.update');
            Route::post('/destroy/{id}', 'Admin\SurveyController@processSurveyDelete')->name('admin.survey.destroy');
        });
        Route::prefix('question')->group(function () {
            Route::get('/', 'Admin\QuestionController@index')->name('admin.question.index');
            Route::get('/create/{survey_id}', 'Admin\QuestionController@viewQuestionAdd')->name('admin.question.create');
            Route::post('/store/{survey_id}', 'Admin\QuestionController@processQuestionAdd')->name('admin.question.store');
            Route::get('/detail/{id}', 'Admin\QuestionController@viewQuestionDetail')->name('admin.question.detail');
            Route::get('/refresh', 'Admin\QuestionController@processQuestionRefresh')->name('admin.question.refresh');
            Route::get('/edit/{id}/{survey_id}', 'Admin\QuestionController@viewQuestionEdit')->name('admin.question.edit');
            Route::post('/update/{id}/{survey_id}', 'Admin\QuestionController@processQuestionEdit')->name('admin.question.update');
            Route::post('/destroy/{id}/{survey_id}', 'Admin\QuestionController@processQuestionDelete')->name('admin.question.destroy');
        });
    });
});
