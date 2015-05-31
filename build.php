<?php
$p = new Phar('TestSuite.phar', FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::KEY_AS_FILENAME, 'TestSuite.phar');
$p->startBuffering();
$p->setStub($p->createDefaultStub('index.php'));
$p->buildFromDirectory(__DIR__ . '/', '$(.*)$');
$p->stopBuffering();
echo "TestSuite.phar archive has been saved";
?>