<?php

require_once __DIR__ . '/testframework.php';

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../modules/database.php';
require_once __DIR__ . '/../modules/page.php';

$testFramework = new TestFramework();

// Test 1: Check database connection
function testDbConnection() {
    global $config;
    try {
        $db = new Database($config["db"]["path"]);
        return assertExpression($db instanceof Database, 'Database connection established', 'Failed to establish database connection');
    } catch (Exception $e) {
        return assertExpression(false, 'Database connection established', 'Failed to establish database connection');
    }
}

// Test 2: Test count method
function testDbCount() {
    global $config;
    $db = new Database($config["db"]["path"]);
    $count = $db->Count('page');
    return assertExpression($count == 3, 'Correct count of pages', 'Incorrect count of pages '.$count);
}

// Test 3: Test create method
function testDbCreate() {
    global $config;
    $db = new Database($config["db"]["path"]);
    $id = $db->Create('page', ['title' => 'New Page', 'content' => 'New Content']);
    $newPage = $db->Read('page', $id);
    return assertExpression($newPage['title'] == 'New Page' && $newPage['content'] == 'New Content', 'Create method works', 'Create method failed');
}

// Test 4: Test read method
function testDbRead() {
    global $config;
    $db = new Database($config["db"]["path"]);
    $page = $db->Read('page', 1);
    return assertExpression($page['id'] == 1 && $page['title'] == 'Page 1', 'Read method works', 'Read method failed');
}

// Test 5: Test update method
function testDbUpdate() {
    global $config;
    $db = new Database($config["db"]["path"]);
    $updateData = ['title' => 'Updated Title', 'content' => 'Updated Content'];
    $updateResult = $db->Update('page', 1, $updateData);
    $updatedPage = $db->Read('page', 1);
    return assertExpression($updatedPage['title'] == 'Updated Title' && $updatedPage['content'] == 'Updated Content', 'Update method works', 'Update method failed');
}

// Test 6: Test delete method
function testDbDelete() {
    global $config;
    $db = new Database($config["db"]["path"]);
    $db->Delete('page', 1);
    $deletedPage = $db->Read('page', 1);
    return assertExpression($deletedPage == false, 'Delete method works', 'Delete method failed');
}

// Test 7: Test page rendering
function testPageRender() {
    $page = new Page(__DIR__ . '/../templates/index.tpl');
    $data = ['title' => 'Test Page', 'content' => 'This is a test page.'];
    $rendered = $page->Render($data);
    return assertExpression(strpos($rendered, 'Test Page') !== false && strpos($rendered, 'This is a test page.') !== false, 'Page render works', 'Page render failed');
}

// Add tests
$testFramework->add('Database connection', 'testDbConnection');
$testFramework->add('Table count', 'testDbCount');
$testFramework->add('Data create', 'testDbCreate');
$testFramework->add('Data read', 'testDbRead');
$testFramework->add('Data update', 'testDbUpdate');
$testFramework->add('Data delete', 'testDbDelete');
$testFramework->add('Page render', 'testPageRender');

// Run tests
$testFramework->run();

echo $testFramework->getResult();
