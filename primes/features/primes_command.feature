Feature: Primes Command Feature
  In order to display the multiplication tables for a set of primes
  As a user
  I need to execute a command that calculates the given number of primes and their multiplication tables

  Scenario: Test command with defaults
    When I run "app:primes"
    Then I should see "/2\s3\s5\s7\s11\s13\s17\s19\s23\s29/" in the output
    And I should see "/58\s87\s145\s203\s319\s377\s493\s551\s667\s841/" in the output
    And I should see "/Elapsed time: /" in the output

  Scenario: Test command with more primes
    When I run "app:primes --count=20"
    Then I should see "/2\s3\s5\s7\s11\s13\s17\s19\s23\s29\s31\s37\s41\s43\s47\s53\s59\s61\s67\s71/" in the output
    And I should see "/142\s213\s355\s497\s781\s923\s1207\s1349\s1633\s2059\s2201\s2627\s2911\s3053\s3337\s3763\s4189\s4331\s4757\s5041/" in the output
    And I should see "/Elapsed time: /" in the output