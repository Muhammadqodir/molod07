<?php

use App\Http\Controllers\Admin\AdministratorsController;
use App\Http\Controllers\Admin\ManageCommentsController;
use App\Http\Controllers\Admin\ManageCoursesController;
use App\Http\Controllers\Admin\ManageBooksController;
use App\Http\Controllers\Admin\ManageEventsController;
use App\Http\Controllers\Admin\ManageGrantsController;
use App\Http\Controllers\Admin\ManageNewsController;
use App\Http\Controllers\Admin\ManagePodcastsController;
use App\Http\Controllers\Admin\ManageVacanciesController;
use App\Http\Controllers\Admin\PartnersController;
use App\Http\Controllers\Admin\SupportController;
use App\Http\Controllers\Admin\FeedbackController as AdminFeedbackController;
use App\Http\Controllers\Admin\YouthController;
use App\Http\Controllers\Auth\RegisterPartnerController;
use App\Http\Controllers\Auth\RegisterYouthController;
use App\Http\Controllers\Profiles\PartnerProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\Profiles\AdminProfileController;
use App\Http\Controllers\Profiles\YouthProfileController;
use App\Http\Controllers\Youth\EventsController;
use App\Http\Controllers\Youth\VacancyController;
use App\Http\Controllers\Youth\PointsController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ViewController;
use App\Http\Controllers\GrantApplicationController;
use App\Http\Controllers\FeedbackController;

Route::get('/', [PagesController::class, 'main'])->name("main");
Route::get('/course/{id}', [PagesController::class, 'coursePage'])->name("course");
Route::get('/event/{id}', [PagesController::class, 'eventPage'])->name("event");
Route::get('/vacancy/{id}', [PagesController::class, 'vacancyPage'])->name("vacancy");
Route::get('/news/{id}', [PagesController::class, 'newsPage'])->name("news");
Route::get('/podcast/{id}', [PagesController::class, 'podcastPage'])->name("podcast");
Route::get('/grants/{id}', [PagesController::class, 'grantsPage'])->name("grant");

Route::get('/courses', [PagesController::class, 'coursesList'])->name("courses.list");
Route::get('/books', [PagesController::class, 'booksList'])->name("books.list");
Route::get('/events', [PagesController::class, 'eventsList'])->name("events.list");
Route::get('/vacancies', [PagesController::class, 'vacanciesList'])->name("vacancies.list");
Route::get('/news', [PagesController::class, 'newsList'])->name("news.list");
Route::get('/podcasts', [PagesController::class, 'podcastsList'])->name("podcasts.list");
Route::get('/grants', [PagesController::class, 'grantsList'])->name("grants.list");

// Opportunities routes
Route::get('/opportunities', [App\Http\Controllers\OpportunityController::class, 'index'])->name('opportunities.index');
Route::get('/opportunities/{opportunity}', [App\Http\Controllers\OpportunityController::class, 'show'])->name('opportunities.show');
Route::get('/ministry/{ministry}/opportunities', [App\Http\Controllers\OpportunityController::class, 'byMinistry'])->name('opportunities.by-ministry');

Route::get('/about', [PagesController::class, 'aboutPage'])->name('about');
Route::get('/contacts', [PagesController::class, 'contactsPage'])->name('contacts');
Route::get('/documents', [PagesController::class, 'documentsPage'])->name('documents');
Route::get('/privacy-policy-new', [PagesController::class, 'privacyPolicyPage'])->name('privacy-policy');
Route::get('/profile-deleted', [PagesController::class, 'profileDeletedPage'])->name('profile.deleted');
Route::get('/partners', [PagesController::class, 'partnersList'])->name('partners');
Route::get('/partner/{id}', [PagesController::class, 'partnerPage'])->name('partner');

Route::middleware('guest')->group(function () {
    Route::get('/register/youth', [RegisterYouthController::class, 'show'])->name('youth.reg');
    Route::post('/register/youth', [RegisterYouthController::class, 'register'])->name("youth.reg.post");

    Route::get('/register/partner', [RegisterPartnerController::class, 'show'])->name('partner.reg');
    Route::post('/register/partner', [RegisterPartnerController::class, 'register'])->name("partner.reg.post");
});


Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'view'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Feedback routes (available for all users)
Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');

//Youth routes
Route::middleware(['auth', 'role:youth'])->group(function () {
    Route::get('/profile/youth', [YouthProfileController::class, 'show'])->name('youth.profile');
    Route::post('/profile/youth/post', [YouthProfileController::class, 'updateProfile'])->name('youth.profile.post');
    Route::delete('/profile/youth/delete', [YouthProfileController::class, 'deleteProfile'])->name('youth.profile.delete');

    Route::post('/event/{id}/register', [EventsController::class, 'registerForEvent'])->name("event.register");
    Route::get('/youth/events', [EventsController::class, 'myEvents'])->name('youth.events');

    // Vacancy routes
    Route::get('/youth/vacancies', [VacancyController::class, 'myVacancies'])->name('youth.vacancies');
    Route::post('/vacancy/{id}/respond', [VacancyController::class, 'respond'])->name('vacancy.respond');

    // Points routes
    Route::get('/youth/points', [PointsController::class, 'index'])->name('youth.points');

    // QR code route
    Route::get('/youth/qrcode', [YouthProfileController::class, 'qrcode'])->name('youth.qrcode');

    // Grant application route
    Route::post('/grant/{id}/apply', [GrantApplicationController::class, 'store'])->name('grant.apply');
});


//Partner routes
Route::middleware(['auth', 'role:partner'])->group(function () {
    Route::get('/profile/partner', [PartnerProfileController::class, 'show'])->name('partner.profile');
    Route::post('/profile/partner/post', [PartnerProfileController::class, 'updateProfile'])->name('partner.profile.post');

    //News
    Route::get('partner/news/list', [ManageNewsController::class, 'show'])->name('partner.news.index');
    Route::get('partner/news/create', [ManageNewsController::class, 'create'])->name('partner.news.create');
    Route::post('partner/news/create', [ManageNewsController::class, 'store'])->name('partner.news.store');
    Route::get('partner/news/edit/{id}', [ManageNewsController::class, 'edit'])->name('partner.news.edit');
    Route::put('partner/news/update/{id}', [ManageNewsController::class, 'update'])->name('partner.news.update');
    Route::post('partner/news/action/archive/{id}', [ManageNewsController::class, 'archive'])->name('partner.news.action.archive');
    Route::post('partner/news/action/remove/{id}', [ManageNewsController::class, 'remove'])->name('partner.news.action.remove');
    Route::get('partner/news/preview/{id}', [ManageNewsController::class, 'preview'])->name('partner.news.preview');

    //Events
    Route::get('partner/events/list', [ManageEventsController::class, 'show'])->name('partner.events.index');
    Route::get('partner/events/create', [ManageEventsController::class, 'create'])->name('partner.events.create');
    Route::post('partner/events/create', [ManageEventsController::class, 'store'])->name('partner.events.store');
    Route::get('partner/events/edit/{id}', [ManageEventsController::class, 'edit'])->name('partner.events.edit');
    Route::put('partner/events/update/{id}', [ManageEventsController::class, 'update'])->name('partner.events.update');
    Route::post('partner/events/action/archive/{id}', [ManageEventsController::class, 'archive'])->name('partner.events.action.archive');
    Route::post('partner/events/action/remove/{id}', [ManageEventsController::class, 'remove'])->name('partner.events.action.remove');
    Route::get('partner/events/preview/{id}', [ManageEventsController::class, 'preview'])->name('partner.events.preview');
    Route::get('partner/events/participants', [ManageEventsController::class, 'getParticipants'])->name('partner.events.participants');
    Route::post('partner/events/participants/approve/{id}', [ManageEventsController::class, 'approveParticipant'])->name('partner.events.participants.approve');
    Route::post('partner/events/participants/reject/{id}', [ManageEventsController::class, 'rejectParticipant'])->name('partner.events.participants.reject');
    Route::post('partner/events/participants/accure/{id}', [ManageEventsController::class, 'accurePoints'])->name('partner.events.participants.accure');

    //Vacancies
    Route::get('partner/vacancies/list', [ManageVacanciesController::class, 'show'])->name('partner.vacancies.index');
    Route::get('partner/vacancies/responses', [ManageVacanciesController::class, 'getResponses'])->name('partner.vacancies.responses');
    Route::get('partner/vacancies/create', [ManageVacanciesController::class, 'create'])->name('partner.vacancies.create');
    Route::post('partner/vacancies/create', [ManageVacanciesController::class, 'store'])->name('partner.vacancies.store');
    Route::get('partner/vacancies/edit/{id}', [ManageVacanciesController::class, 'edit'])->name('partner.vacancies.edit');
    Route::put('partner/vacancies/update/{id}', [ManageVacanciesController::class, 'update'])->name('partner.vacancies.update');
    Route::post('partner/vacancies/action/archive/{id}', [ManageVacanciesController::class, 'archive'])->name('partner.vacancies.action.archive');
    Route::post('partner/vacancies/action/remove/{id}', [ManageVacanciesController::class, 'remove'])->name('partner.vacancies.action.remove');
    Route::get('partner/vacancies/preview/{id}', [ManageVacanciesController::class, 'preview'])->name('partner.vacancies.preview');

    // Grants
    Route::get('partner/grants/list', [ManageGrantsController::class, 'show'])->name('partner.grants.index');
    Route::get('partner/grants/responses', [ManageGrantsController::class, 'getResponses'])->name('partner.grants.responses');
    Route::get('partner/grants/create', [ManageGrantsController::class, 'create'])->name('partner.grants.create');
    Route::post('partner/grants/create', [ManageGrantsController::class, 'store'])->name('partner.grants.store');
    Route::get('partner/grants/edit/{id}', [ManageGrantsController::class, 'edit'])->name('partner.grants.edit');
    Route::put('partner/grants/update/{id}', [ManageGrantsController::class, 'update'])->name('partner.grants.update');
    Route::post('partner/grants/action/archive/{id}', [ManageGrantsController::class, 'archive'])->name('partner.grants.action.archive');
    Route::get('partner/grants/preview/{id}', [ManageGrantsController::class, 'preview'])->name('partner.grants.preview');

});

//Admin routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/profile/admin', [AdminProfileController::class, 'show'])->name('admin.profile');
    Route::post('/profile/admin/post', [AdminProfileController::class, 'updateProfile'])->name('admin.profile.post');

    //Administrators
    Route::get('admin/manage/administrators', [AdministratorsController::class, 'show'])->name('admin.manage.administrators');
    Route::get('admin/manage/administrators/create', [AdministratorsController::class, 'createShow'])->name('admin.manage.administrators.create');
    Route::post('admin/manage/administrators/create/post', [AdministratorsController::class, 'createAdminPost'])->name('admin.manage.administrators.create.post');
    Route::post('admin/manage/administrators/remove', [AdministratorsController::class, 'remove'])->name('admin.manage.administrators.remove');
    Route::post('admin/manage/administrators/block', [AdministratorsController::class, 'block'])->name('admin.manage.administrators.block');
    Route::post('admin/manage/administrators/unblock', [AdministratorsController::class, 'unblock'])->name('admin.manage.administrators.unblock');
    Route::post('admin/manage/administrators/generate-password', [AdministratorsController::class, 'generateNewPassword'])->name('admin.manage.administrators.reset-password');

    //Youth
    Route::get('admin/manage/youth', [YouthController::class, 'show'])->name('admin.manage.youth');
    Route::post('admin/manage/youth/remove', [YouthController::class, 'remove'])->name('admin.manage.youth.remove');
    Route::post('admin/manage/youth/block', [YouthController::class, 'block'])->name('admin.manage.youth.block');
    Route::post('admin/manage/youth/unblock', [YouthController::class, 'unblock'])->name('admin.manage.youth.unblock');

    //Partners
    Route::get('admin/manage/partners', [PartnersController::class, 'show'])->name('admin.manage.partners');
    Route::post('admin/manage/partners/remove', [PartnersController::class, 'remove'])->name('admin.manage.partners.remove');
    Route::post('admin/manage/partners/block', [PartnersController::class, 'block'])->name('admin.manage.partners.block');
    Route::post('admin/manage/partners/unblock', [PartnersController::class, 'unblock'])->name('admin.manage.partners.unblock');
    Route::post('admin/manage/partners/generate-password', [PartnersController::class, 'generateNewPassword'])->name('admin.manage.partners.reset-password');

    //Events
    Route::get('admin/events/list', [ManageEventsController::class, 'show'])->name('admin.events.index')->defaults('status', 'approved');
    Route::get('admin/events/requests', [ManageEventsController::class, 'show'])->name('admin.events.requests')->defaults('status', 'pending');
    Route::get('admin/events/archive', [ManageEventsController::class, 'show'])->name('admin.events.archive')->defaults('status', 'archived');
    Route::get('admin/events/create', [ManageEventsController::class, 'create'])->name('admin.events.create');
    Route::post('admin/events/create', [ManageEventsController::class, 'store'])->name('admin.events.store');
    Route::get('admin/events/edit/{id}', [ManageEventsController::class, 'edit'])->name('admin.events.edit');
    Route::put('admin/events/update/{id}', [ManageEventsController::class, 'update'])->name('admin.events.update');
    Route::get('admin/events/preview/{id}', [ManageEventsController::class, 'preview'])->name('admin.events.preview');
    Route::post('admin/events/approve/{id}', [ManageEventsController::class, 'approve'])->name('admin.events.approve');
    Route::post('admin/events/reject/{id}', [ManageEventsController::class, 'reject'])->name('admin.events.reject');
    Route::post('admin/events/action/archive/{id}', [ManageEventsController::class, 'archive'])->name('admin.events.action.archive');

    //News
    Route::get('admin/news/list', [ManageNewsController::class, 'show'])->name('admin.news.index')->defaults('status', 'approved');
    Route::get('admin/news/requests', [ManageNewsController::class, 'show'])->name('admin.news.requests')->defaults('status', 'pending');
    Route::get('admin/news/archive', [ManageNewsController::class, 'show'])->name('admin.news.archive')->defaults('status', 'archived');
    Route::get('admin/news/create', [ManageNewsController::class, 'create'])->name('admin.news.create');
    Route::post('admin/news/create', [ManageNewsController::class, 'store'])->name('admin.news.store');
    Route::get('admin/news/edit/{id}', [ManageNewsController::class, 'edit'])->name('admin.news.edit');
    Route::put('admin/news/update/{id}', [ManageNewsController::class, 'update'])->name('admin.news.update');
    Route::get('admin/news/preview/{id}', [ManageNewsController::class, 'preview'])->name('admin.news.preview');
    Route::post('admin/news/approve/{id}', [ManageNewsController::class, 'approve'])->name('admin.news.approve');
    Route::post('admin/news/reject/{id}', [ManageNewsController::class, 'reject'])->name('admin.news.reject');
    Route::post('admin/news/action/archive/{id}', [ManageNewsController::class, 'archive'])->name('admin.news.action.archive');

    //Comments
    Route::get('admin/comments/list', [ManageCommentsController::class, 'show'])->name('admin.comments.index')->defaults('status', 'approved');
    Route::get('admin/comments/requests', [ManageCommentsController::class, 'show'])->name('admin.comments.requests')->defaults('status', 'pending');
    Route::get('admin/comments/rejected', [ManageCommentsController::class, 'show'])->name('admin.comments.rejected')->defaults('status', 'rejected');
    Route::get('admin/comments/preview/{id}', [ManageCommentsController::class, 'preview'])->name('admin.comments.preview');
    Route::post('admin/comments/approve/{id}', [ManageCommentsController::class, 'approve'])->name('admin.comments.approve');
    Route::post('admin/comments/reject/{id}', [ManageCommentsController::class, 'reject'])->name('admin.comments.reject');
    Route::post('admin/comments/delete/{id}', [ManageCommentsController::class, 'delete'])->name('admin.comments.delete');
    Route::post('admin/comments/block-user/{id}', [ManageCommentsController::class, 'block'])->name('admin.comments.block');

    //Grants
    Route::get('admin/grants/list', [ManageGrantsController::class, 'show'])->name('admin.grants.index')->defaults('status', 'approved');
    Route::get('admin/grants/requests', [ManageGrantsController::class, 'show'])->name('admin.grants.requests')->defaults('status', 'pending');
    Route::get('admin/grants/archive', [ManageGrantsController::class, 'show'])->name('admin.grants.archive')->defaults('status', 'archived');
    Route::get('admin/grants/create', [ManageGrantsController::class, 'create'])->name('admin.grants.create');
    Route::post('admin/grants/create', [ManageGrantsController::class, 'store'])->name('admin.grants.store');
    Route::get('admin/grants/edit/{id}', [ManageGrantsController::class, 'edit'])->name('admin.grants.edit');
    Route::put('admin/grants/update/{id}', [ManageGrantsController::class, 'update'])->name('admin.grants.update');
    Route::get('admin/grants/preview/{id}', [ManageGrantsController::class, 'preview'])->name('admin.grants.preview');
    Route::post('admin/grants/approve/{id}', [ManageGrantsController::class, 'approve'])->name('admin.grants.approve');
    Route::post('admin/grants/reject/{id}', [ManageGrantsController::class, 'reject'])->name('admin.grants.reject');
    Route::post('admin/grants/action/archive/{id}', [ManageGrantsController::class, 'archive'])->name('admin.grants.action.archive');

    //Podcasts
    Route::get('admin/podcasts/list', [ManagePodcastsController::class, 'show'])->name('admin.podcasts.index')->defaults('status', 'approved');
    Route::get('admin/podcasts/requests', [ManagePodcastsController::class, 'show'])->name('admin.podcasts.requests')->defaults('status', 'pending');
    Route::get('admin/podcasts/archive', [ManagePodcastsController::class, 'show'])->name('admin.podcasts.archive')->defaults('status', 'archived');
    Route::get('admin/podcasts/create', [ManagePodcastsController::class, 'create'])->name('admin.podcasts.create');
    Route::post('admin/podcasts/create', [ManagePodcastsController::class, 'store'])->name('admin.podcasts.store');
    Route::get('admin/podcasts/edit/{id}', [ManagePodcastsController::class, 'edit'])->name('admin.podcasts.edit');
    Route::put('admin/podcasts/update/{id}', [ManagePodcastsController::class, 'update'])->name('admin.podcasts.update');
    Route::get('admin/podcasts/preview/{id}', [ManagePodcastsController::class, 'preview'])->name('admin.podcasts.preview');
    Route::post('admin/podcasts/approve/{id}', [ManagePodcastsController::class, 'approve'])->name('admin.podcasts.approve');
    Route::post('admin/podcasts/reject/{id}', [ManagePodcastsController::class, 'reject'])->name('admin.podcasts.reject');
    Route::post('admin/podcasts/action/archive/{id}', [ManagePodcastsController::class, 'archive'])->name('admin.podcasts.action.archive');
    Route::delete('admin/podcasts/{id}', [ManagePodcastsController::class, 'destroy'])->name('admin.podcasts.destroy');

    //Education/Courses
    Route::get('admin/education/list', [ManageCoursesController::class, 'show'])->name('admin.education.index')->defaults('status', 'approved');
    Route::get('admin/education/requests', [ManageCoursesController::class, 'show'])->name('admin.education.requests')->defaults('status', 'pending');
    Route::get('admin/education/archive', [ManageCoursesController::class, 'show'])->name('admin.education.archive')->defaults('status', 'archived');
    Route::get('admin/education/create', [ManageCoursesController::class, 'create'])->name('admin.education.create');
    Route::post('admin/education/create', [ManageCoursesController::class, 'store'])->name('admin.education.store');
    Route::get('admin/education/edit/{id}', [ManageCoursesController::class, 'edit'])->name('admin.education.edit');
    Route::put('admin/education/update/{id}', [ManageCoursesController::class, 'update'])->name('admin.education.update');
    Route::get('admin/education/preview/{id}', [ManageCoursesController::class, 'preview'])->name('admin.education.preview');
    Route::post('admin/education/approve/{id}', [ManageCoursesController::class, 'approve'])->name('admin.education.approve');
    Route::post('admin/education/reject/{id}', [ManageCoursesController::class, 'reject'])->name('admin.education.reject');
    Route::post('admin/education/action/archive/{id}', [ManageCoursesController::class, 'archive'])->name('admin.education.action.archive');
    Route::delete('admin/education/{id}', [ManageCoursesController::class, 'destroy'])->name('admin.education.destroy');

    //Books (Book Shelf / Книжная полка)
    Route::get('admin/books/list', [ManageBooksController::class, 'show'])->name('admin.books.index')->defaults('status', 'approved');
    Route::get('admin/books/requests', [ManageBooksController::class, 'show'])->name('admin.books.requests')->defaults('status', 'pending');
    Route::get('admin/books/archive', [ManageBooksController::class, 'show'])->name('admin.books.archive')->defaults('status', 'archived');
    Route::get('admin/books/create', [ManageBooksController::class, 'create'])->name('admin.books.create');
    Route::post('admin/books/create', [ManageBooksController::class, 'store'])->name('admin.books.store');
    Route::get('admin/books/edit/{id}', [ManageBooksController::class, 'edit'])->name('admin.books.edit');
    Route::put('admin/books/update/{id}', [ManageBooksController::class, 'update'])->name('admin.books.update');
    Route::post('admin/books/approve/{id}', [ManageBooksController::class, 'approve'])->name('admin.books.approve');
    Route::post('admin/books/reject/{id}', [ManageBooksController::class, 'reject'])->name('admin.books.reject');
    Route::post('admin/books/action/archive/{id}', [ManageBooksController::class, 'archive'])->name('admin.books.action.archive');
    Route::delete('admin/books/{id}', [ManageBooksController::class, 'destroy'])->name('admin.books.destroy');

    //Vacancies
    Route::get('admin/vacancies/list', [ManageVacanciesController::class, 'show'])->name('admin.vacancies.index')->defaults('status', 'approved');
    Route::get('admin/vacancies/requests', [ManageVacanciesController::class, 'show'])->name('admin.vacancies.requests')->defaults('status', 'pending');
    Route::get('admin/vacancies/archive', [ManageVacanciesController::class, 'show'])->name('admin.vacancies.archive')->defaults('status', 'archived');
    Route::get('admin/vacancies/responses', [ManageVacanciesController::class, 'getAllResponses'])->name('admin.vacancies.responses');
    Route::get('admin/vacancies/create', [ManageVacanciesController::class, 'create'])->name('admin.vacancies.create');
    Route::post('admin/vacancies/create', [ManageVacanciesController::class, 'store'])->name('admin.vacancies.store');
    Route::get('admin/vacancies/edit/{id}', [ManageVacanciesController::class, 'edit'])->name('admin.vacancies.edit');
    Route::put('admin/vacancies/update/{id}', [ManageVacanciesController::class, 'update'])->name('admin.vacancies.update');
    Route::get('admin/vacancies/preview/{id}', [ManageVacanciesController::class, 'preview'])->name('admin.vacancies.preview');
    Route::post('admin/vacancies/approve/{id}', [ManageVacanciesController::class, 'approve'])->name('admin.vacancies.approve');
    Route::post('admin/vacancies/reject/{id}', [ManageVacanciesController::class, 'reject'])->name('admin.vacancies.reject');
    Route::post('admin/vacancies/action/archive/{id}', [ManageVacanciesController::class, 'archive'])->name('admin.vacancies.action.archive');


    //Support
    Route::get('admin/support', [SupportController::class, 'show'])->name('admin.support');

    //Feedback
    Route::prefix('admin/feedback')->group(function () {
        Route::get('/', [AdminFeedbackController::class, 'index'])->name('admin.feedback.index');
        Route::get('/{feedback}', [AdminFeedbackController::class, 'show'])->name('admin.feedback.show');
        Route::patch('/{feedback}/status', [AdminFeedbackController::class, 'updateStatus'])->name('admin.feedback.update-status');
        Route::delete('/{feedback}', [AdminFeedbackController::class, 'destroy'])->name('admin.feedback.destroy');
        Route::post('/bulk-action', [AdminFeedbackController::class, 'bulkAction'])->name('admin.feedback.bulk-action');
    });

    Route::get('/admin/get-user/{id}', function ($id) {
        $id = ltrim($id, '0');
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'Пользователь не найден'], 404);
        }
        $profile = $user->getProfile();
        return response()->json([
            'name' => $profile?->name ?? $profile?->person_name ?? '',
            'l_name' => $profile?->l_name ?? $profile?->person_lname ?? '',
            'phone' => $profile?->phone ?? '',
            'email' => $user->email,
        ]);
    })->middleware(['auth', 'role:admin,partner'])->name('admin.get.user');

    Route::get('/admin/get-partner/{id}', function ($id) {
        $id = ltrim($id, '0');
        $partner = User::where('role', 'partner')->find($id);
        if (!$partner) {
            return response()->json(['error' => 'Партнер не найден'], 404);
        }
        $profile = $partner->getProfile();
        return response()->json([
            'name' => $partner->getFullName(),
            'email' => $partner->email,
            'phone' => $profile?->phone ?? '',
            'address' => $profile?->org_address ?? '',
        ]);
    })->middleware(['auth', 'role:admin,partner'])->name('admin.get.partner');

    Route::get('/admin/search-partners', function () {
        $query = request('q');
        if (!$query || strlen($query) < 2) {
            return response()->json(['partners' => []]);
        }

        $partners = User::where('role', 'partner')
            ->whereHas('partnersProfile', function ($q) use ($query) {
                $q->where('org_name', 'like', '%' . $query . '%');
            })
            ->orWhere(function ($q) use ($query) {
                $q->where('role', 'partner')
                  ->whereHas('partnersProfile', function ($subQ) use ($query) {
                      $subQ->where('person_name', 'like', '%' . $query . '%')
                           ->orWhere('person_lname', 'like', '%' . $query . '%');
                  });
            })
            ->limit(10)
            ->get()
            ->map(function ($partner) {
                return [
                    'id' => $partner->id,
                    'name' => $partner->partnersProfile->org_name ?? $partner->getFullName(),
                ];
            });

        return response()->json(['partners' => $partners]);
    })->middleware(['auth', 'role:admin'])->name('admin.search.partners');

    // Ministries
    Route::resource('admin/ministries', App\Http\Controllers\Admin\ManageMinistriesController::class, [
        'as' => 'admin'
    ]);

    // Opportunities
    Route::resource('admin/opportunities', App\Http\Controllers\Admin\ManageOpportunitiesController::class, [
        'as' => 'admin'
    ]);
});

// API маршруты для комментариев, лайков и просмотров
Route::middleware(['web'])->group(function () {
    // Комментарии
    Route::post('/api/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::get('/api/comments', [CommentController::class, 'index'])->name('comments.index');
    Route::delete('/api/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Лайки
    Route::post('/api/likes/toggle', [LikeController::class, 'toggle'])->name('likes.toggle');

    // Просмотры
    Route::post('/api/views/track', [ViewController::class, 'track'])->name('views.track');
});

//Profile redirect
Route::middleware('auth')->get('/profile', function () {
    $user = Auth::user();
    return match ($user->role) {
        'youth' => redirect()->route('youth.profile'),
        'admin' => redirect()->route('admin.profile'),
        'partner' => redirect()->route('partner.profile'),
        default => abort(403),
    };
})->name('profile');
