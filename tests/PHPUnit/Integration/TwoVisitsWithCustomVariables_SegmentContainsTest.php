<?php
/**
 * Piwik - Open source web analytics
 *
 * @link    http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 * @version $Id$
 */

/**
 * Tests use of custom variable segments.
 */
class Test_Piwik_Integration_TwoVisitsWithCustomVariables_SegmentContains extends IntegrationTestCase
{
    protected static $dateTime  = '2010-01-03 11:22:33';
    protected static $width     = 1111;
    protected static $height    = 222;

    protected static $idSite    = 1;
    protected static $idGoal1   = 1;
    protected static $idGoal2   = 2;
    protected static $visitorId = '61e8cc2d51fea26d';

    protected static $useEscapedQuotes  = true;
    protected static $doExtraQuoteTests = false;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        try {
            self::setUpWebsitesAndGoals();
            self::trackVisits();
        } catch(Exception $e) {
            // Skip whole test suite if an error occurs while setup
            throw new PHPUnit_Framework_SkippedTestSuiteError($e->getMessage());
        }
    }

    public function getOutputPrefix()
    {
        return 'twoVisitsWithCustomVariables';
    }

    /**
     * @dataProvider getApiForTesting
     * @group        Integration
     * @group        TwoVisitsWithCustomVariables_SegmentContains
     */
    public function testApi($api, $params)
    {
        $this->runApiTests($api, $params);
    }

    public function getApiForTesting()
    {
        $return = array();

        $api            = array('Actions.getPageUrls', 'Actions.getPageTitles', 'VisitsSummary.get');
        $segmentsToTest = array(
            // array( SegmentString , TestSuffix , Array of API to test)
            array("pageTitle=@*_)%", '_SegmentPageTitleContainsStrangeCharacters', array('Actions.getPageTitles', 'VisitsSummary.get')),
            array("pageUrl=@user/profile", '_SegmentPageUrlContains', $api),
            array("pageTitle=@Profile pa", '_SegmentPageTitleContains', $api),
            array("pageUrl!@user/profile", '_SegmentPageUrlExcludes', $api),
            array("pageTitle!@Profile pa", '_SegmentPageTitleExcludes', $api),
        );

        foreach ($segmentsToTest as $segment) {
            // Also test "Page URL / Page title CONTAINS string" feature
            $return[] = array($segment[2],
                array('idSite'       => self::$idSite, 'date' => self::$dateTime, 'periods' => array('day'),
                      'setDateLastN' => false,
                      'segment'      => $segment[0],
                      'testSuffix'   => $segment[1])
            );
        }
        return $return;
    }

    protected static function setUpWebsitesAndGoals()
    {
        // tests run in UTC, the Tracker in UTC
        self::createWebsite(self::$dateTime);
        Piwik_Goals_API::getInstance()->addGoal(self::$idSite, 'triggered js', 'manually', '', '');
        Piwik_Goals_API::getInstance()->addGoal(self::$idSite, 'second goal', 'manually', '', '');
    }

    protected static function trackVisits()
    {
        $dateTime = self::$dateTime;
        $idSite   = self::$idSite;
        $idGoal   = self::$idGoal1;
        $idGoal2  = self::$idGoal2;

        $visitorA = self::getTracker($idSite, $dateTime, $defaultInit = true);
        // Used to test actual referer + keyword position in Live!
        $visitorA->setUrlReferrer(urldecode('http://www.google.com/url?sa=t&source=web&cd=1&ved=0CB4QFjAA&url=http%3A%2F%2Fpiwik.org%2F&rct=j&q=this%20keyword%20should%20be%20ranked&ei=V8WfTePkKKLfiALrpZWGAw&usg=AFQjCNF_MGJRqKPvaKuUokHtZ3VvNG9ALw&sig2=BvKAdCtNixsmfNWXjsNyMw'));

        // no campaign, but a search engine to attribute the goal conversion to
        $attribution = array(
            '',
            '',
            1302306504,
            'http://www.google.com/search?q=piwik&ie=utf-8&oe=utf-8&aq=t&rls=org.mozilla:en-GB:official&client=firefox-a'
        );
        $visitorA->setAttributionInfo(json_encode($attribution));

        $visitorA->setResolution(self::$width, self::$height);

        // At first, visitor custom var is set to LoggedOut
        $visitorA->setForceVisitDateTime(Piwik_Date::factory($dateTime)->addHour(0.1)->getDatetime());
        $visitorA->setUrl('http://example.org/homepage');
        $visitorA->setCustomVariable($id = 1, $name = 'VisitorType', $value = 'LoggedOut');
        self::checkResponse($visitorA->doTrackPageView('Homepage'));
        self::checkResponse($visitorA->doTrackGoal($idGoal2));

        // After login, set to LoggedIn, should overwrite previous value
        $visitorA->setForceVisitDateTime(Piwik_Date::factory($dateTime)->addHour(0.2)->getDatetime());
        $visitorA->setUrl('http://example.org/user/profile');
        $visitorA->setCustomVariable($id = 1, $name = 'VisitorType', $value = 'LoggedIn');
        $visitorA->setCustomVariable($id = 4, $name = 'Status user', $value = 'Loggedin', $scope = 'page');
        if (self::$useEscapedQuotes) {
            $lookingAtProfile = 'looking at &quot;profile page&quot;';
        } else {
            $lookingAtProfile = 'looking at profile page';
        }
        $visitorA->setCustomVariable($id = 5, $name = 'Status user', $value = $lookingAtProfile, $scope = 'page');
        self::checkResponse($visitorA->doTrackPageView('Profile page'));

        $visitorA->setCustomVariable($id = 2, $name = 'SET WITH EMPTY VALUE', $value = '');
        $visitorA->setCustomVariable($id = 1, $name = 'Language', $value = 'FR', $scope = 'page');
        $visitorA->setCustomVariable($id = 2, $name = 'SET WITH EMPTY VALUE PAGE SCOPE', $value = '', $scope = 'page');
        $visitorA->setCustomVariable($id = 4, $name = 'Status user', $value = "looking at \"profile page\"", $scope = 'page');
        $visitorA->setCustomVariable($id = 3, $name = 'Value will be VERY long and truncated', $value = 'abcdefghijklmnopqrstuvwxyz----abcdefghijklmnopqrstuvwxyz----abcdefghijklmnopqrstuvwxyz----abcdefghijklmnopqrstuvwxyz----abcdefghijklmnopqrstuvwxyz----abcdefghijklmnopqrstuvwxyz----abcdefghijklmnopqrstuvwxyz----abcdefghijklmnopqrstuvwxyz----abcdefghijklmnopqrstuvwxyz----abcdefghijklmnopqrstuvwxyz----abcdefghijklmnopqrstuvwxyz----abcdefghijklmnopqrstuvwxyz----abcdefghijklmnopqrstuvwxyz----abcdefghijklmnopqrstuvwxyz----abcdefghijklmnopqrstuvwxyz----abcdefghijklmnopqrstuvwxyz----abcdefghijklmnopqrstuvwxyz----abcdefghijklmnopqrstuvwxyz----');
        self::checkResponse($visitorA->doTrackPageView('Profile page for user *_)%'));
        self::checkResponse($visitorA->doTrackGoal($idGoal));

        if (self::$doExtraQuoteTests) {
            $visitorA->setCustomVariable($id = 2, $name = 'var1', $value = 'looking at "profile page"',
                $scope = 'page');
            $visitorA->setCustomVariable($id = 3, $name = 'var2', $value = '\'looking at "\profile page"\'',
                $scope = 'page');
            $visitorA->setCustomVariable($id = 4, $name = 'var3', $value = '\\looking at "\profile page"\\',
                $scope = 'page');
            self::checkResponse($visitorA->doTrackPageView('Concurrent page views'));
        }

        // -
        // Second new visitor on Idsite 1: one page view
        $visitorB = self::getTracker($idSite, $dateTime, $defaultInit = true);
        $visitorB->setVisitorId(self::$visitorId);
        $visitorB->setUrlReferrer('');

        $attribution = array(
            ' CAMPAIGN NAME -%20YEAH! ',
            ' CAMPAIGN%20KEYWORD - RIGHT... ',
            1302306504,
            'http://www.example.org/test/really?q=yes'
        );
        $visitorB->setAttributionInfo(json_encode($attribution));
        $visitorB->setResolution(self::$width, self::$height);
        $visitorB->setUserAgent('Mozilla/5.0 (Windows; U; Windows NT 5.1; en-GB; rv:1.9.0.6) Gecko/2009011913 Firefox/3.0.6');
        $visitorB->setForceVisitDateTime(Piwik_Date::factory($dateTime)->addHour(1)->getDatetime());
        $visitorB->setCustomVariable($id = 1, $name = 'VisitorType', $value = 'LoggedOut');
        $visitorB->setCustomVariable($id = 2, $name = 'Othercustom value which should be truncated abcdefghijklmnopqrstuvwxyz', $value = 'abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz');
        $visitorB->setCustomVariable($id = -2, $name = 'not tracked', $value = 'not tracked');
        $visitorB->setCustomVariable($id = 6, $name = 'not tracked', $value = 'not tracked');
        $visitorB->setCustomVariable($id = 6, $name = array('not tracked'), $value = 'not tracked');
        $visitorB->setUrl('http://example.org/homepage');
        self::checkResponse($visitorB->doTrackGoal($idGoal, 1000));

        $visitorB->setForceVisitDateTime(Piwik_Date::factory($dateTime)->addHour(1.1)->getDatetime());
        self::checkResponse($visitorB->doTrackPageView('Homepage'));

        // DIFFERENT test -
        // testing that starting the visit with an outlink works (doesn't trigger errors)
        $visitorB->setForceVisitDateTime(Piwik_Date::factory($dateTime)->addHour(2)->getDatetime());
        self::checkResponse($visitorB->doTrackAction('http://test.com', 'link'));
    }
}

