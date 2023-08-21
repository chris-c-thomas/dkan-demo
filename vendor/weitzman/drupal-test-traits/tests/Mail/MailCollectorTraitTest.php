<?php

namespace weitzman\DrupalTestTraits\Tests\Mail;

use weitzman\DrupalTestTraits\ExistingSiteBase;
use weitzman\DrupalTestTraits\Mail\MailCollectionAssertTrait;
use weitzman\DrupalTestTraits\Mail\MailCollectionTrait;

/**
 * @coversDefaultClass \weitzman\DrupalTestTraits\Mail\MailCollectionTrait
 */
class MailCollectorTraitTest extends ExistingSiteBase
{
    use MailCollectionTrait;
    use MailCollectionAssertTrait;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->startMailCollection();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        $this->restoreMailSettings();
        parent::tearDown();
    }

    public function testMailCollection()
    {
        $this->visit('/contact');
        $subject = $this->randomString();
        $page = $this->getCurrentPage();
        $page->fillField('Your name', $this->randomString());
        $page->fillField('Your email address', 'foo@example.com');
        $page->fillField('Subject', $subject);
        $page->fillField('Message', $this->randomString());
        $page->pressButton('Send message');

        $mails = $this->getMails();
        $this->assertCount(1, $mails);
        $this->assertMail('module', 'contact');
        $this->assertMail('subject', '[Website feedback] ' . $subject);

        $this->assertMailCollection()
            ->seekToModule('contact')
            ->seekToRecipient('admin@example.com')
            ->countEquals(1)
            ->subjectEquals('[Website feedback] ' . $subject);
    }
}
