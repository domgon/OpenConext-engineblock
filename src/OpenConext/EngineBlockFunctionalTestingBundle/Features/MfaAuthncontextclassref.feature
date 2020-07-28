Feature:
  In order to support MFA
  As EngineBlock
  I want to support configurable authncontextclassrefs for certain IdP SP combinations

  Background:
    Given an EngineBlock instance on "vm.openconext.org"
      And no registered SPs
      And no registered Idps
      And an Identity Provider named "SSO-IdP"
      And a Service Provider named "SSO-SP"

    Scenario: The configured authn method should be set as authncontextclassref if configured with the IdP configuration mapping
      Given the IdP "SSO-IdP" is configured for MFA authn method "http://schemas.microsoft.com/claims/multipleauthn" for SP "SSO-SP"
      When I log in at "SSO-SP"
        And I pass through EngineBlock
      Then the url should match "functional-testing/SSO-IdP/sso"
        And the AuthnRequest to submit should match xpath '/samlp:AuthnRequest/samlp:RequestedAuthnContext/saml:AuthnContextClassRef[text()="http://schemas.microsoft.com/claims/multipleauthn"]'

    Scenario: The configured authn method should not be set as authncontextclassref if not configured in the IdP configuration mapping
      When I log in at "SSO-SP"
        And I pass through EngineBlock
    Then the url should match "functional-testing/SSO-IdP/sso"
      And the response should not contain "http://schemas.microsoft.com/claims/multipleauthn"

  Scenario: The configured authn method should also be set for unsolicited logins if configured in the IdP configuration mapping
    Given the IdP "SSO-IdP" is configured for MFA authn method "http://schemas.microsoft.com/claims/multipleauthn" for SP "SSO-SP"
    When An IdP initiated Single Sign on for SP "SSO-SP" is triggered by IdP "SSO-IdP"
      And I pass through EngineBlock
    Then the url should match "functional-testing/SSO-IdP/sso"
    And the AuthnRequest to submit should match xpath '/samlp:AuthnRequest/samlp:RequestedAuthnContext/saml:AuthnContextClassRef[text()="http://schemas.microsoft.com/claims/multipleauthn"]'

    Scenario: A login should succeed if the configured authn method is set as authncontextclassref in the IdP response
      Given the IdP "SSO-IdP" is configured for MFA authn method "http://schemas.microsoft.com/claims/multipleauthn" for SP "SSO-SP"
        And the IdP "SSO-IdP" sends AuthnContextClassRef with value "http://schemas.microsoft.com/claims/multipleauthn"
      When I log in at "SSO-SP"
        And I pass through EngineBlock
        And I pass through the IdP
        And I give my consent
        And I pass through EngineBlock
      Then the url should match "/functional-testing/SSO-SP/acs"

  Scenario: A login should succeed if the configured authn method is set as one of the values in the http://schemas.microsoft.com/claims/authnmethodsreferences attribute in the IdP response
    Given the IdP "SSO-IdP" is configured for MFA authn method "http://schemas.microsoft.com/claims/multipleauthn" for SP "SSO-SP"
      And the IdP "SSO-IdP" sends attribute "http://schemas.microsoft.com/claims/authnmethodsreferences" with values "http://schemas.microsoft.com/claims/multipleauthn" and xsi:type is "xs:string"
    When I log in at "SSO-SP"
      And I pass through EngineBlock
      And I pass through the IdP
      And I give my consent
      And I pass through EngineBlock
    Then the url should match "/functional-testing/SSO-SP/acs"

  Scenario: A login should fail if the configured authn method is not in the IdP response as authnclassref or as a value in the http://schemas.microsoft.com/claims/authnmethodsreferences attribute
    Given the IdP "SSO-IdP" is configured for MFA authn method "http://schemas.microsoft.com/claims/multipleauthn" for SP "SSO-SP"
    When I log in at "SSO-SP"
      And I pass through EngineBlock
      And I pass through the IdP
    Then I should see "Error - An error occurred"
      And the url should match "/feedback/unknown-error"
