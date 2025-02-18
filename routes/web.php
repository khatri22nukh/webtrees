<?php

/**
 * webtrees: online genealogy
 * Copyright (C) 2019 webtrees development team
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * @var Map $router
 */
declare(strict_types=1);

namespace Fisharebest\Webtrees;

use Aura\Router\Map;
use Aura\Router\RouterContainer;
use Fig\Http\Message\RequestMethodInterface;
use Fisharebest\Webtrees\Http\Middleware\AuthAdministrator;
use Fisharebest\Webtrees\Http\Middleware\AuthEditor;
use Fisharebest\Webtrees\Http\Middleware\AuthLoggedIn;
use Fisharebest\Webtrees\Http\Middleware\AuthManager;
use Fisharebest\Webtrees\Http\Middleware\AuthMember;
use Fisharebest\Webtrees\Http\Middleware\AuthModerator;
use Fisharebest\Webtrees\Http\RequestHandlers\CleanDataFolder;
use Fisharebest\Webtrees\Http\RequestHandlers\ControlPanel;
use Fisharebest\Webtrees\Http\RequestHandlers\CreateTreeAction;
use Fisharebest\Webtrees\Http\RequestHandlers\CreateTreePage;
use Fisharebest\Webtrees\Http\RequestHandlers\DeletePath;
use Fisharebest\Webtrees\Http\RequestHandlers\DeleteTreeAction;
use Fisharebest\Webtrees\Http\RequestHandlers\DeleteUser;
use Fisharebest\Webtrees\Http\RequestHandlers\HelpText;
use Fisharebest\Webtrees\Http\RequestHandlers\HomePage;
use Fisharebest\Webtrees\Http\RequestHandlers\PhpInformation;
use Fisharebest\Webtrees\Http\RequestHandlers\RedirectFamilyPhp;
use Fisharebest\Webtrees\Http\RequestHandlers\RedirectGedRecordPhp;
use Fisharebest\Webtrees\Http\RequestHandlers\RedirectIndividualPhp;
use Fisharebest\Webtrees\Http\RequestHandlers\LoginAction;
use Fisharebest\Webtrees\Http\RequestHandlers\LoginPage;
use Fisharebest\Webtrees\Http\RequestHandlers\Logout;
use Fisharebest\Webtrees\Http\RequestHandlers\MasqueradeAsUser;
use Fisharebest\Webtrees\Http\RequestHandlers\ModuleAction;
use Fisharebest\Webtrees\Http\RequestHandlers\PasswordRequestAction;
use Fisharebest\Webtrees\Http\RequestHandlers\PasswordRequestPage;
use Fisharebest\Webtrees\Http\RequestHandlers\PasswordResetAction;
use Fisharebest\Webtrees\Http\RequestHandlers\PasswordResetPage;
use Fisharebest\Webtrees\Http\RequestHandlers\Ping;
use Fisharebest\Webtrees\Http\RequestHandlers\PrivacyPolicy;
use Fisharebest\Webtrees\Http\RequestHandlers\RedirectMediaViewerPhp;
use Fisharebest\Webtrees\Http\RequestHandlers\RedirectNotePhp;
use Fisharebest\Webtrees\Http\RequestHandlers\RedirectRepoPhp;
use Fisharebest\Webtrees\Http\RequestHandlers\RedirectSourcePhp;
use Fisharebest\Webtrees\Http\RequestHandlers\RegisterAction;
use Fisharebest\Webtrees\Http\RequestHandlers\RegisterPage;
use Fisharebest\Webtrees\Http\RequestHandlers\ReorderChildrenAction;
use Fisharebest\Webtrees\Http\RequestHandlers\ReorderChildrenPage;
use Fisharebest\Webtrees\Http\RequestHandlers\ReorderMediaAction;
use Fisharebest\Webtrees\Http\RequestHandlers\ReorderMediaPage;
use Fisharebest\Webtrees\Http\RequestHandlers\ReorderNamesAction;
use Fisharebest\Webtrees\Http\RequestHandlers\ReorderNamesPage;
use Fisharebest\Webtrees\Http\RequestHandlers\ReorderSpousesAction;
use Fisharebest\Webtrees\Http\RequestHandlers\ReorderSpousesPage;
use Fisharebest\Webtrees\Http\RequestHandlers\SearchAdvancedPage;
use Fisharebest\Webtrees\Http\RequestHandlers\SearchAdvancedAction;
use Fisharebest\Webtrees\Http\RequestHandlers\SearchGeneralAction;
use Fisharebest\Webtrees\Http\RequestHandlers\SearchGeneralPage;
use Fisharebest\Webtrees\Http\RequestHandlers\SearchPhoneticAction;
use Fisharebest\Webtrees\Http\RequestHandlers\SearchPhoneticPage;
use Fisharebest\Webtrees\Http\RequestHandlers\SearchQuickAction;
use Fisharebest\Webtrees\Http\RequestHandlers\SearchReplaceAction;
use Fisharebest\Webtrees\Http\RequestHandlers\SearchReplacePage;
use Fisharebest\Webtrees\Http\RequestHandlers\SelectDefaultTree;
use Fisharebest\Webtrees\Http\RequestHandlers\SelectLanguage;
use Fisharebest\Webtrees\Http\RequestHandlers\SelectTheme;
use Fisharebest\Webtrees\Http\RequestHandlers\VerifyEmail;

use function app;

$router = app(RouterContainer::class)->getMap();

// Admin routes.
$router->attach('', '/admin', static function (Map $router) {
    $router->extras([
        'middleware' => [AuthAdministrator::class]
    ]);

    $router->get(ControlPanel::class, '/control-panel', ControlPanel::class);
    $router->get('admin-fix-level-0-media', '/fix-level-0-media', 'Admin\FixLevel0MediaController::fixLevel0Media');
    $router->post('admin-fix-level-0-media-action', '/fix-level-0-media', 'Admin\FixLevel0MediaController::fixLevel0MediaAction');
    $router->get('admin-fix-level-0-media-data', '/fix-level-0-media-data', 'Admin\FixLevel0MediaController::fixLevel0MediaData');
    $router->get('admin-webtrees1-thumbs', '/webtrees1-thumbs', 'Admin\ImportThumbnailsController::webtrees1Thumbnails');
    $router->post('admin-webtrees1-thumbs-action', '/webtrees1-thumbs', 'Admin\ImportThumbnailsController::webtrees1ThumbnailsAction');
    $router->get('admin-webtrees1-thumbs-data', '/webtrees1-thumbs-data', 'Admin\ImportThumbnailsController::webtrees1ThumbnailsData');
    $router->get('modules', '/modules', 'Admin\ModuleController::list');
    $router->post('modules-update', '/modules', 'Admin\ModuleController::update');
    $router->get('analytics', '/analytics', 'Admin\ModuleController::listAnalytics');
    $router->post('analytics-update', '/analytics', 'Admin\ModuleController::updateAnalytics');
    $router->get('blocks', '/blocks', 'Admin\ModuleController::listBlocks');
    $router->post('blocks-update', '/blocks', 'Admin\ModuleController::updateBlocks');
    $router->get('charts', '/charts', 'Admin\ModuleController::listCharts');
    $router->post('charts-update', '/charts', 'Admin\ModuleController::updateCharts');
    $router->get('lists', '/lists', 'Admin\ModuleController::listLists');
    $router->post('lists-update', '/lists', 'Admin\ModuleController::updateLists');
    $router->get('footers', '/footers', 'Admin\ModuleController::listFooters');
    $router->post('footers-update', 'footers', 'Admin\ModuleController::updateFooters');
    $router->get('history', '/history', 'Admin\ModuleController::listHistory');
    $router->post('history-update', '/history', 'Admin\ModuleController::updateHistory');
    $router->get('menus', '/menus', 'Admin\ModuleController::listMenus');
    $router->post('menus-update', '/menus', 'Admin\ModuleController::updateMenus');
    $router->get('languages', '/languages', 'Admin\ModuleController::listLanguages');
    $router->post('languages-update', '/languages', 'Admin\ModuleController::updateLanguages');
    $router->get('reports', '/reports', 'Admin\ModuleController::listReports');
    $router->post('reports-update', '/reports', 'Admin\ModuleController::updateReports');
    $router->get('sidebars', '/sidebars', 'Admin\ModuleController::listSidebars');
    $router->post('sidebars-update', '/sidebars', 'Admin\ModuleController::updateSidebars');
    $router->get('themes', '/themes', 'Admin\ModuleController::listThemes');
    $router->post('themes-update', '/themes', 'Admin\ModuleController::updateThemes');
    $router->get('tabs', '/tabs', 'Admin\ModuleController::listTabs');
    $router->post('tabs-update', '/tabs', 'Admin\ModuleController::updateTabs');
    $router->post('delete-module-settings', '/delete-module-settings', 'Admin\ModuleController::deleteModuleSettings');
    $router->get('map-data', '/map-data', 'Admin\LocationController::mapData');
    $router->get('map-data-edit', '/map-data-edit', 'Admin\LocationController::mapDataEdit');
    $router->post('map-data-update', '/map-data-edit', 'Admin\LocationController::mapDataSave');
    $router->post('map-data-delete', '/map-data-delete', 'Admin\LocationController::mapDataDelete');
    $router->get('locations-export', '/locations-export', 'Admin\LocationController::exportLocations');
    $router->get('locations-import', '/locations-import', 'Admin\LocationController::importLocations');
    $router->post('locations-import-action', '/locations-import', 'Admin\LocationController::importLocationsAction');
    $router->post('locations-import-from-tree', '/locations-import-from-tree', 'Admin\LocationController::importLocationsFromTree');
    $router->get('map-provider', '/map-provider', 'Admin\MapProviderController::mapProviderEdit');
    $router->post('map-provider-action', '/map-provider', 'Admin\MapProviderController::mapProviderSave');
    $router->get('admin-media', '/admin-media', 'Admin\MediaController::index');
    $router->get('admin-media-data', '/admin-media-data', 'Admin\MediaController::data');
    $router->post('admin-media-delete', '/admin-media-delete', 'Admin\MediaController::delete');
    $router->get('admin-media-upload', '/admin-media-upload', 'Admin\MediaController::upload');
    $router->post('admin-media-upload-action', '/admin-media-upload', 'Admin\MediaController::uploadAction');
    $router->get('upgrade', '/upgrade', 'Admin\UpgradeController::wizard');
    $router->post('upgrade-action', '/upgrade', 'Admin\UpgradeController::step');
    $router->get('admin-users', '/admin-users', 'Admin\UsersController::index');
    $router->get('admin-users-data', '/admin-users-data', 'Admin\UsersController::data');
    $router->get('admin-users-create', '/admin-users-create', 'Admin\UsersController::create');
    $router->post('admin-users-create-action', '/admin-users-create', 'Admin\UsersController::save');
    $router->get('admin-users-edit', '/admin-users-edit', 'Admin\UsersController::edit');
    $router->post('admin-users-update', '/admin-users-edit', 'Admin\UsersController::update');
    $router->get('admin-users-cleanup', '/admin-users-cleanup', 'Admin\UsersController::cleanup');
    $router->post('admin-users-cleanup-action', '/admin-users-cleanup', 'Admin\UsersController::cleanupAction');
    $router->get(CleanDataFolder::class, '/clean', CleanDataFolder::class);
    $router->post(DeletePath::class, '/delete-path', DeletePath::class);
    $router->get('admin-site-preferences', '/admin-site-preferences', 'AdminSiteController::preferencesForm');
    $router->post('admin-site-preferences-update', '/admin-site-preferences', 'AdminSiteController::preferencesSave');
    $router->get('admin-site-mail', '/admin-site-mail', 'AdminSiteController::mailForm');
    $router->post('admin-site-mail-update', '/admin-site-mail', 'AdminSiteController::mailSave');
    $router->get('admin-site-registration', '/admin-site-registration', 'AdminSiteController::registrationForm');
    $router->post('admin-site-registration-update', '/admin-site-registration', 'AdminSiteController::registrationSave');
    $router->get('broadcast', '/broadcast', 'MessageController::broadcastPage');
    $router->post('broadcast-action', '/broadcast', 'MessageController::broadcastAction');
    $router->get(PhpInformation::class, '/information', PhpInformation::class);
    $router->post('masquerade', '/masquerade/{user_id}', MasqueradeAsUser::class);
    $router->get('admin-site-logs', '/logs', 'AdminSiteController::logs');
    $router->get('admin-site-logs-data', '/logs-data', 'AdminSiteController::logsData');
    $router->post('admin-site-logs-delete', '/logs-delete', 'AdminSiteController::logsDelete');
    $router->get('admin-site-logs-export', '/logs-export', 'AdminSiteController::logsExport');
    $router->get(CreateTreePage::class, '/trees/create', CreateTreePage::class);
    $router->post(CreateTreeAction::class, '/trees/create', CreateTreeAction::class);
    $router->post(SelectDefaultTree::class, '/trees/default/{tree}', SelectDefaultTree::class);
    $router->get('tree-page-default-edit', '/trees/default-blocks', 'HomePageController::treePageDefaultEdit');
    $router->post('tree-page-default-update', '/trees/default-blocks', 'HomePageController::treePageDefaultUpdate');
    $router->post(DeleteTreeAction::class, '/trees/delete/{tree}', DeleteTreeAction::class);
    $router->get('admin-trees-merge', '/trees/merge', 'AdminTreesController::merge');
    $router->post('admin-trees-merge-action', '/trees/merge', 'AdminTreesController::mergeAction');
    $router->post('admin-trees-sync', '/trees/sync', 'AdminTreesController::synchronize');
    $router->get('unused-media-thumbnail', '/unused-media-thumbnail', 'MediaFileController::unusedMediaThumbnail');
    $router->post('delete-user', '/users/delete/{user_id}', DeleteUser::class);
    $router->get('user-page-default-edit', '/user-page-default-edit', 'HomePageController::userPageDefaultEdit');
    $router->post('user-page-default-update', '/user-page-default-update', 'HomePageController::userPageDefaultUpdate');
    $router->get('user-page-user-edit', '/user-page-user-edit', 'HomePageController::userPageUserEdit');
    $router->post('user-page-user-update', '/user-page-user-update', 'HomePageController::userPageUserUpdate');
});

// Manager routes (without a tree).
$router->attach('', '', static function (Map $router) {
    $router->get('manage-trees', '/trees/manage', 'AdminTreesController::index');
});

// Manager routes.
$router->attach('', '/tree/{tree}', static function (Map $router) {
    $router->extras([
        'middleware' => [AuthManager::class]
    ]);

    $router->get('admin-changes-log', '/changes-log', 'Admin\ChangesLogController::changesLog');
    $router->get('admin-changes-log-data', '/changes-log-data', 'Admin\ChangesLogController::changesLogData');
    $router->get('admin-changes-log-download', '/changes-log-download', 'Admin\ChangesLogController::changesLogDownload');
    $router->get('admin-trees-check', '/check', 'AdminTreesController::check');
    $router->get('admin-trees-duplicates', '/duplicates', 'AdminTreesController::duplicates');
    $router->get('admin-trees-export', '/export', 'AdminTreesController::export');
    $router->get('admin-trees-download', '/download', 'AdminTreesController::exportClient');
    $router->post('admin-trees-export-action', '/export', 'AdminTreesController::exportServer');
    $router->get('admin-trees-import', '/import', 'AdminTreesController::importForm');
    $router->post('admin-trees-import-action', '/import', 'AdminTreesController::importAction');
    $router->get('admin-trees-places', '/places', 'AdminTreesController::places');
    $router->post('admin-trees-places-action', '/places', 'AdminTreesController::placesAction');
    $router->get('admin-trees-preferences', '/preferences', 'AdminTreesController::preferences');
    $router->post('admin-trees-preferences-update', '/preferences', 'AdminTreesController::preferencesUpdate');
    $router->get('admin-trees-renumber', '/renumber', 'AdminTreesController::renumber');
    $router->post('admin-trees-renumber-action', '/renumber', 'AdminTreesController::renumberAction');
    $router->get('admin-trees-unconnected', '/aunconnected', 'AdminTreesController::unconnected');
    $router->get('tree-page-edit', '/tree-page-edit', 'HomePageController::treePageEdit');
    $router->post('import', '/load', 'GedcomFileController::import');
    $router->post('tree-page-update', '/tree-page-update', 'HomePageController::treePageUpdate');
    $router->get('merge-records', '/merge-records', 'AdminController::mergeRecords');
    $router->post('merge-records-update', '/merge-records', 'AdminController::mergeRecordsAction');
    $router->get('tree-page-block-edit', '/tree-page-block-edit', 'HomePageController::treePageBlockEdit');
    $router->post('tree-page-block-update', '/tree-page-block-edit', 'HomePageController::treePageBlockUpdate');
    $router->get('tree-preferences', '/preferences', 'AdminController::treePreferencesEdit');
    $router->post('tree-preferences-update', '/preferences', 'AdminController::treePreferencesUpdate');
    $router->get('tree-privacy', '/privacy', 'AdminController::treePrivacyEdit');
    $router->post('tree-privacy-update', '/privacy', 'AdminController::treePrivacyUpdate');
});

// Manager routes.
$router->attach('', '/tree/{tree}', static function (Map $router) {
    $router->extras([
        'middleware' => [AuthModerator::class]
    ]);

    $router->get('show-pending', '/show-pending', 'PendingChangesController::showChanges');
    $router->post('accept-pending', '/accept-pending', 'PendingChangesController::acceptChange');
    $router->post('reject-pending', '/reject-pending', 'PendingChangesController::rejectChange');
    $router->post('accept-all-pending', '/accept-all-pending', 'PendingChangesController::acceptAllChanges');
    $router->post('reject-all-pending', '/reject-all-pending', 'PendingChangesController::rejectAllChanges');
});

// Editor routes.
$router->attach('', '/tree/{tree}', static function (Map $router) {
    $router->extras([
        'middleware' => [AuthEditor::class]
    ]);

    $router->get('add-media-file', '/add-media-file', 'EditMediaController::addMediaFile');
    $router->post('add-media-file-update', '/add-media-file', 'EditMediaController::addMediaFileAction');
    $router->get('edit-media-file', '/edit-media-file', 'EditMediaController::editMediaFile');
    $router->post('edit-media-file-update', '/edit-media-file', 'EditMediaController::editMediaFileAction');
    $router->get('create-media-object', '/create-media-object', 'EditMediaController::createMediaObject');
    $router->post('create-media-object-update', '/create-media-object', 'EditMediaController::createMediaObjectAction');
    $router->post('create-media-from-file', '/create-media-from-file', 'EditMediaController::createMediaObjectFromFileAction');
    $router->get('link-media-to-individual', '/link-media-to-individual', 'EditMediaController::linkMediaToIndividual');
    $router->get('link-media-to-family', '/link-media-to-family', 'EditMediaController::linkMediaToFamily');
    $router->get('link-media-to-source', '/link-media-to-source', 'EditMediaController::linkMediaToSource');
    $router->post('link-media-to-record', '/link-media-to-record', 'EditMediaController::linkMediaToRecordAction');
    $router->get('create-note-object', '/create-note-object', 'EditNoteController::createNoteObject');
    $router->post('create-note-object-action', '/create-note-object', 'EditNoteController::createNoteObjectAction');
    $router->get('edit-note-object', '/edit-note-object', 'EditNoteController::editNoteObject');
    $router->post('edit-note-object-action', '/edit-note-object', 'EditNoteController::updateNoteObject');
    $router->get('create-repository', '/create-repository', 'EditRepositoryController::createRepository');
    $router->post('create-repository-action', '/create-repository', 'EditRepositoryController::createRepositoryAction');
    $router->get('create-source', '/create-source', 'EditSourceController::createSource');
    $router->post('create-source-action', '/create-source', 'EditSourceController::createSourceAction');
    $router->get('create-submitter', '/create-submitter', 'EditSubmitterController::createSubmitter');
    $router->post('create-submitter-action', '/create-submitter', 'EditSubmitterController::createSubmitterAction');
    $router->get(ReorderChildrenPage::class, '/reorder-children/{xref}', ReorderChildrenPage::class);
    $router->post(ReorderChildrenAction::class, '/reorder-children/{xref}', ReorderChildrenAction::class);
    $router->get(ReorderMediaPage::class, '/reorder-media/{xref}', ReorderMediaPage::class);
    $router->post(ReorderMediaAction::class, '/reorder-media/{xref}', ReorderMediaAction::class);
    $router->get(ReorderNamesPage::class, '/reorder-names/{xref}', ReorderNamesPage::class);
    $router->post(ReorderNamesAction::class, '/reorder-names/{xref}', ReorderNamesAction::class);
    $router->get(ReorderSpousesPage::class, '/reorder-spouses/{xref}', ReorderSpousesPage::class);
    $router->post(ReorderSpousesAction::class, '/reorder-spouses/{xref}', ReorderSpousesAction::class);
    $router->get('edit-raw-record', '/edit-raw-record', 'EditGedcomRecordController::editRawRecord');
    $router->post('edit-raw-record-action', '/edit-raw-record', 'EditGedcomRecordController::editRawRecordAction');
    $router->get('edit-raw-fact', '/edit-raw-fact', 'EditGedcomRecordController::editRawFact');
    $router->post('edit-raw-fact-update', '/edit-raw-fact', 'EditGedcomRecordController::editRawFactAction');
    $router->post('copy-fact', '/copy-fact/{xref}/{fact_id}', 'EditGedcomRecordController::copyFact');
    $router->post('delete-fact', '/delete-fact/{xref}/{fact_id}', 'EditGedcomRecordController::deleteFact');
    $router->post('paste-fact', '/paste-fact', 'EditGedcomRecordController::pasteFact');
    $router->post('delete-record', '/delete/{xref}', 'EditGedcomRecordController::deleteRecord');
    $router->get('add-fact', '/add-fact', 'EditGedcomRecordController::addFact');
    $router->get('edit-fact', '/edit-fact', 'EditGedcomRecordController::editFact');
    $router->post('update-fact', '/update-fact', 'EditGedcomRecordController::updateFact');
    $router->get(SearchReplacePage::class, '/search-replace', SearchReplacePage::class);
    $router->post(SearchReplaceAction::class, '/search-replace', SearchReplaceAction::class);
    $router->get('add-child-to-family', '/add-child-to-family', 'EditFamilyController::addChild');
    $router->post('add-child-to-family-action', '/add-child-to-family', 'EditFamilyController::addChildAction');
    $router->get('add-spouse-to-family', '/add-spouse-to-family', 'EditFamilyController::addSpouse');
    $router->post('add-spouse-to-family-action', '/add-spouse-to-family', 'EditFamilyController::addSpouseAction');
    $router->get('change-family-members', '/change-family-members', 'EditFamilyController::changeFamilyMembers');
    $router->post('change-family-members-action', '/change-family-members', 'EditFamilyController::changeFamilyMembersAction');
    $router->get('add-child-to-individual', '/add-child-to-individual', 'EditIndividualController::addChild');
    $router->post('add-child-to-individual-action', '/add-child-to-individual', 'EditIndividualController::addChildAction');
    $router->get('add-parent-to-individual', '/add-parent-to-individual', 'EditIndividualController::addParent');
    $router->post('add-parent-to-individual-action', '/add-parent-to-individual', 'EditIndividualController::addParentAction');
    $router->get('add-spouse-to-individual', '/add-spouse-to-individual', 'EditIndividualController::addSpouse');
    $router->post('add-spouse-to-individual-action', '/add-spouse-to-individual', 'EditIndividualController::addSpouseAction');
    $router->get('add-unlinked-individual', '/add-unlinked-individual', 'EditIndividualController::addUnlinked');
    $router->post('add-unlinked-individual-action', '/add-unlinked-individual', 'EditIndividualController::addUnlinkedAction');
    $router->get('link-child-to-family', '/link-child-to-family', 'EditIndividualController::linkChildToFamily');
    $router->post('link-child-to-family-action', '/link-child-to-family', 'EditIndividualController::linkChildToFamilyAction');
    $router->get('link-spouse-to-individual', '/link-spouse-to-individual', 'EditIndividualController::linkSpouseToIndividual');
    $router->post('link-spouse-to-individual-action', '/link-spouse-to-individual', 'EditIndividualController::linkSpouseToIndividualAction');
    $router->get('edit-name', '/edit-name', 'EditIndividualController::editName');
    $router->post('edit-name-action', '/edit-name-update', 'EditIndividualController::editNameAction');
    $router->get('add-name', '/add-name', 'EditIndividualController::addName');
    $router->post('add-name-action', '/add-name-update', 'EditIndividualController::addNameAction');
});

// Member routes.
$router->attach('', '/tree/{tree}', static function (Map $router) {
    $router->extras([
        'middleware' => [AuthMember::class]
    ]);

    $router->get('user-page', '/my-page', 'HomePageController::userPage');
    $router->get('user-page-block', '/my-page-block', 'HomePageController::userPageBlock');
    $router->get('user-page-edit', '/my-page-edit', 'HomePageController::userPageEdit');
    $router->post('user-page-update', '/my-page-edit', 'HomePageController::userPageUpdate');
    $router->get('user-page-block-edit', '/my-page-block-edit', 'HomePageController::userPageBlockEdit');
    $router->post('user-page-block-update', '/my-page-block-edit', 'HomePageController::userPageBlockUpdate');
});

// User routes.
$router->attach('', '', static function (Map $router) {
    $router->extras([
        'middleware' => [AuthLoggedIn::class]
    ]);

    $router->get('my-account', '/my-account', 'AccountController::edit');
    $router->post('my-account-update', '/my-account', 'AccountController::update');
    $router->post('delete-account', '/delete-account', 'AccountController::delete');
});

// Public routes.
$router->attach('', '/tree/{tree}', static function (Map $router) {
    $router->get('tree-page', '/', 'HomePageController::treePage');
    $router->post('accept-changes', '/accept/{xref}', 'PendingChangesController::acceptChanges');
    $router->post('accept-all-changes', '/accept-all-changes', 'PendingChangesController::acceptAllChanges');
    $router->get('autocomplete-folder', '/autocomplete-folder', 'AutocompleteController::folder');
    $router->get('autocomplete-page', '/autocomplete-page', 'AutocompleteController::page');
    $router->get('autocomplete-place', '/autocomplete-place', 'AutocompleteController::place');
    $router->get('calendar', '/calendar/{view}', 'CalendarController::page');
    $router->get('calendar-events', '/calendar-events/{view}', 'CalendarController::calendar');
    $router->get('contact', '/contact', 'MessageController::contactPage');
    $router->post('contact-action', '/contact', 'MessageController::contactAction');
    $router->get('family', '/family/{xref}{/slug}', 'FamilyController::show');
    $router->get('individual', '/individual/{xref}{/slug}', 'IndividualController::show');
    $router->get('media-thumbnail', '/media-thumbnail', 'MediaFileController::mediaThumbnail');
    $router->get('media-download', '/media-download', 'MediaFileController::mediaDownload');
    $router->get('media', '/media/{xref}{/slug}', 'MediaController::show');
    $router->get('message', '/message', 'MessageController::messagePage');
    $router->post('message-action', '/message', 'MessageController::messageAction');
    $router->get('note', '/note/{xref}{/slug}', 'NoteController::show');
    $router->get('record', '/record/{xref}{/slug}', 'GedcomRecordController::show');
    $router->get('repository', '/repository/{xref}{/slug}', 'RepositoryController::show');
    $router->post('reject-changes', '/reject/{xref}', 'PendingChangesController::rejectChanges');
    $router->post('reject-all-changes', '/reject-all-changes', 'PendingChangesController::rejectAllChanges');
    $router->get('report-list', '/report-list', 'ReportEngineController::reportList');
    $router->get('report-setup', '/report-setup', 'ReportEngineController::reportSetup');
    $router->get('report-run', '/report-run', 'ReportEngineController::reportRun');
    $router->get(SearchQuickAction::class, '/search-quick', SearchQuickAction::class);
    $router->get(SearchAdvancedPage::class, '/search-advanced', SearchAdvancedPage::class);
    $router->post(SearchAdvancedAction::class, '/search-advanced', SearchAdvancedAction::class);
    $router->get(SearchGeneralPage::class, '/search-general', SearchGeneralPage::class);
    $router->post(SearchGeneralAction::class, '/search-general', SearchGeneralAction::class);
    $router->get(SearchPhoneticPage::class, '/search-phonetic', SearchPhoneticPage::class);
    $router->post(SearchPhoneticAction::class, '/search-phonetic', SearchPhoneticAction::class);
    $router->post('select2-family', '/select2-family', 'AutocompleteController::select2Family');
    $router->post('select2-individual', '/select2-individual', 'AutocompleteController::select2Individual');
    $router->post('select2-media', '/select2-media', 'AutocompleteController::select2MediaObject');
    $router->post('select2-note', '/select2-note', 'AutocompleteController::select2Note');
    $router->post('select2-source', '/select2-source', 'AutocompleteController::select2Source');
    $router->post('select2-submitter', '/select2-submitter', 'AutocompleteController::select2Submitter');
    $router->post('select2-repository', '/select2-repository', 'AutocompleteController::select2Repository');
    $router->get('source', '/source/{xref}{/slug}', 'SourceController::show');
    $router->get('individual-tab', '/tab-{module}/{xref}', 'IndividualController::tab');
    $router->get('tree-page-block', '/tree-page-block', 'HomePageController::treePageBlock');
    $router->get('example', '/…');
});

$router->get('module', '/module/{module}/{action}{/tree}', ModuleAction::class)
    ->allows(RequestMethodInterface::METHOD_POST);

$router->get(HelpText::class, '/help/{topic}', HelpText::class);
$router->post(SelectLanguage::class, '/language/{language}', SelectLanguage::class);
$router->get(LoginPage::class, '/login{/tree}', LoginPage::class);
$router->post(LoginAction::class, '/login{/tree}', LoginAction::class);
$router->post(Logout::class, '/logout', Logout::class);
$router->get(PasswordRequestPage::class, '/password-request', PasswordRequestPage::class);
$router->post(PasswordRequestAction::class, '/password-request', PasswordRequestAction::class);
$router->get(PasswordResetPage::class, '/password-reset', PasswordResetPage::class);
$router->post(PasswordResetAction::class, '/password-reset', PasswordResetAction::class);
$router->get(Ping::class, '/ping', Ping::class);
$router->get(RegisterPage::class, '/register', RegisterPage::class);
$router->post(RegisterAction::class, '/register', RegisterAction::class);
$router->post(SelectTheme::class, '/theme/{theme}', SelectTheme::class);
$router->get(VerifyEmail::class, '/verify', VerifyEmail::class);
$router->get(PrivacyPolicy::class, '/privacy-policy', PrivacyPolicy::class);
$router->get(HomePage::class, '/', HomePage::class);

// Legacy URLs from older software.
$router->get(RedirectFamilyPhp::class, '/family.php', RedirectFamilyPhp::class);
$router->get(RedirectGedRecordPhp::class, '/gedrecord.php', RedirectGedRecordPhp::class);
$router->get(RedirectIndividualPhp::class, '/individual.php', RedirectIndividualPhp::class);
$router->get(RedirectMediaViewerPhp::class, '/mediaviewer.php', RedirectMediaViewerPhp::class);
$router->get(RedirectNotePhp::class, '/note.php', RedirectNotePhp::class);
$router->get(RedirectRepoPhp::class, '/repository.php', RedirectRepoPhp::class);
$router->get(RedirectSourcePhp::class, '/source.php', RedirectSourcePhp::class);
