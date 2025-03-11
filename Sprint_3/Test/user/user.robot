*** Settings ***
*** Settings ***
Documentation
Resource    ../resource/resource.robot
Suite Teardown    Close Browser

*** Test Cases ***

TC001: Open Main Page
    Open Browser To Home Page

TC002: Go to Research Project Page
    Click Element    xpath=//*[@id="collapsibleNavbar"]/ul/li[3]/a
    Page Should Contain Element   xpath=//p[contains(text(), 'โครงการบริการวิชาการ/ โครงการวิจัย')]
    Sleep    5s