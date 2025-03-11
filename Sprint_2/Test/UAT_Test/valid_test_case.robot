*** Settings ***
Documentation
Resource    resource.robot
Test Teardown    Close Browser
Library    SeleniumLibrary

*** Variables ***
${FILE_PATH}    E:\\AI_Assignment_bfs.png


*** Test Cases ***
TC001: Open Login Page
    Open Browser To Login Page
    [Teardown]    Close Browser

TC002: Input Login Page
    Open Browser To Login Page
    Input Username and Password    thanlao@kku.ac.th    123456789
    Click Element    xpath=/html/body/div/div[2]/div[2]/form/div[4]/button
    Page Should Contain    Dashboard
    [Teardown]    Close Browser

TC003: Go to Add Highlihgt Page
    Open Browser To Login Page
    Input Username and Password    thanlao@kku.ac.th    123456789
    Click Element    xpath=/html/body/div/div[2]/div[2]/form/div[4]/button
    Page Should Contain    Dashboard
    Wait Until Element Is Visible    xpath=//*[@id="sidebar"]/ul/li[5]/a/span
    Click Element    xpath=//*[@id="sidebar"]/ul/li[5]/a/span
    Wait Until Element Is Visible    xpath=//*[@id="ManagePublications"]/ul/li[2]
    Click Element    xpath=//*[@id="ManagePublications"]/ul/li[2]
    Wait Until Element Is Visible    xpath=/html/body/div/div/div/div/div/div/div/div/div[1]/i
    Click Element    xpath=/html/body/div/div/div/div/div/div/div/div/div[1]/i
    Wait Until Page Contains Element    xpath=/html/body/div/div/div/div/div/div/div/h4
    [Teardown]    Close Browser

# TC004: Choose Highlight
#     Open Browser To Login Page
#     Input Username and Password    thanlao@kku.ac.th    123456789
#     Click Element    xpath=/html/body/div/div[2]/div[2]/form/div[4]/button
#     Click Element    xpath=//*[@id="sidebar"]/ul/li[5]/a/span
#     Wait Until Element Is Visible    xpath=//*[@id="ManagePublications"]/ul/li[1]/a
#     Click Element    xpath=//*[@id="ManagePublications"]/ul/li[1]/a
#     Click Element    xpath=//*[@id="highlight-1"]
#     Click Element    xpath=//*[@id="all-highlight-4"]
#     Click Element    xpath=//*[@id="saveButton"]

TC004: Open Add Banner Page
    Open Browser To Login Page
    Input Username and Password    thanlao@kku.ac.th    123456789
    Click Element    xpath=/html/body/div/div[2]/div[2]/form/div[4]/button
    Click Element    xpath=//*[@id="sidebar"]/ul/li[5]/a/span
    Wait Until Element Is Visible    xpath=//*[@id="ManagePublications"]/ul/li[2]/a
    Click Element    xpath=//*[@id="ManagePublications"]/ul/li[2]/a
    Click Element    xpath=/html/body/div/div/div/div/div/div/div/div/div[1]
    Wait Until Page Contains Element    xpath=/html/body/div/div/div/div/div/div/div/h4
    [Teardown]    Close Browser

TC005: Add new Banner
    Open Add Banner Page
    Choose File    xpath=//*[@id="bannerImageInput"]    ${FILE_PATH}
    Input Text    xpath=/html/body/div/div/div/div/div/div/div/form/div[3]/div/input    test1
    Input Text    xpath=/html/body/div/div/div/div/div/div/div/form/div[4]/div/textarea    testtest
    Scroll Element Into View    xpath=//*[@id="add-tag-btn"]
    Click Element    xpath=//*[@id="add-tag-btn"]
    Wait Until Element Is Visible    xpath=//*[@id="tagRow0"]/td/div/select
    Select From List By Index    xpath=//*[@id="tagRow0"]/td/div/select    1
    Scroll Element Into View    xpath=//*[@id="add-btn2"]
    Click Element    xpath=//*[@id="add-btn2"]
    Choose File    xpath=//*[@id="albumInput0"]    ${FILE_PATH}
    Scroll Element Into View    xpath=/html/body/div/div/div/div/div/div[1]/p
    Wait Until Page Contains Element    xpath=/html/body/div/div/div/div/div/div[1]/p
    [Teardown]    Close Browser

TC006: Click Link to Highlight
    Open Browser To Home Page
    Wait Until Element Is Visible    //*[@id="carouselExampleIndicators"]/div[2]/div[1]/a
    Click Element    //*[@id="carouselExampleIndicators"]/div[2]/div[1]/a
    Wait Until Page Contains Element    xpath=/html/body/div[1]/div/div/div/div/h2
    [Teardown]    Close Browser

TC007: Click Navigator Highlight
    Open Browser To Home Page
    Click Element    xpath=//*[@id="collapsibleNavbar"]/ul/li[5]/a
    Wait Until Page Contains Element    xpath=/html/body/div/h2
    [Teardown]    Close Browser

TC008: Open Highlight in Highlight Home
    Open Browser To Home Page
    Click Element    xpath=//*[@id="collapsibleNavbar"]/ul/li[5]/a
    Wait Until Page Contains Element    xpath=/html/body/div/h2
    Click Element    xpath=/html/body/div/div/div[1]/div/img
    Wait Until Page Contains Element    xpath=/html/body/div[1]/div/div/div/div/h2
    [Teardown]    Close Browser