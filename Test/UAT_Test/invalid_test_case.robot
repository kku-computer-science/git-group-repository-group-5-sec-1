*** Settings ***
Documentation
Resource    resource.robot
Test Teardown    Close Browser
Library    SeleniumLibrary

*** Test Cases ***
TC001: Login Fail by invalid username 
    Open Browser To Login Page
    Input Username And Password    Thanapon2@kku.ac.th    123456789
    Click Element    xpath=/html/body/div/div[2]/div[2]/form/div[4]/button
    Page Should Contain     Login Failed: Your user ID or password is incorrect
    [Teardown]    Close Browser
   
TC002: Login Fail by invalid password 
    Open Browser To Login Page
    Input Username And Password    Thanaphon@kku.ac.th    12345678
    Click Element    xpath=/html/body/div/div[2]/div[2]/form/div[4]/button
    Page Should Contain     Login Failed: Your user ID or password is incorrect
    [Teardown]    Close Browser

TC003: Login Fail by invalid password 
    Open Browser To Login Page
    Input Text    xpath=//*[@id="username"]    thanlao@kku.ac.th
    Click Element    xpath=/html/body/div/div[2]/div[2]/form/div[4]/button
    Page Should Contain     Login Failed: Your user ID or password is incorrect
    [Teardown]    Close Browser

TC004: Login Fail by invalid password 
    Open Browser To Login Page
    Input Text    xpath=//*[@id="password"]    123456789
    Click Element    xpath=/html/body/div/div[2]/div[2]/form/div[4]/button
    Page Should Contain     Login Failed: Your user ID or password is incorrect
    [Teardown]    Close Browser
