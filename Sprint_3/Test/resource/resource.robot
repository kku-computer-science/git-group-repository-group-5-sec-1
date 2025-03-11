*** Settings ***
Documentation     A resource file with reusable keywords and variables.
...
...               The system specific keywords created here form our own
...               domain specific language. They utilize keywords provided
...               by the imported SeleniumLibrary.
Library           SeleniumLibrary

*** Variables ***
${BROWSER}        Chrome
${DELAY}          0.2
${VALID USER}     demo
${VALID PASSWORD}    mode
${LOGIN URL}      https://cs05sec167.cpkkuhost.com/login
${USERNAME_FIELD}   id=username
${PASSWORD_FIELD}   id=password
${LOGIN_BUTTON}     xpath=//*[@id="collapsibleNavbar"]/span/a


*** Keywords ***
Open Browser To Login Page
    Open Browser    ${LOGIN URL}    ${BROWSER}
    Maximize Browser Window
    Set Selenium Speed    ${DELAY}
    
Open Browser To Home Page
    Open Browser    https://cs05sec167.cpkkuhost.com    ${BROWSER}
    Maximize Browser Window
    Set Selenium Speed    ${DELAY}

Input Username and Password
    [Arguments]    ${username}    ${password}
    Input Text    ${USERNAME_FIELD}    ${username}
    Input Text    ${PASSWORD_FIELD}    ${password}

Open Add Banner Page
     Open Browser To Login Page
    Input Username and Password    thanlao@kku.ac.th    123456789
    Click Element    xpath=/html/body/div/div[2]/div[2]/form/div[4]/button
    Click Element    xpath=//*[@id="sidebar"]/ul/li[5]/a/span
    Wait Until Element Is Visible    xpath=//*[@id="ManagePublications"]/ul/li[2]/a
    Click Element    xpath=//*[@id="ManagePublications"]/ul/li[2]/a
    Click Element    xpath=/html/body/div/div/div/div/div/div/div/div/div[1]
    Wait Until Page Contains Element    xpath=/html/body/div/div/div/div/div/div/div/h4

Go To Research Project
    Click Element    xpath=//*[@id="sidebar"]/ul/li[6]/a     #Research Project
    Click Element    xpath=/html/body/div/div/div/div/div/div/div/a     #Button Add

Go to Edit Research Project
    Click Element    xpath=//*[@id="sidebar"]/ul/li[6]/a     #Research Project
    Scroll Element Into View    xpath=//*[@id="example1"]/tbody[1]/tr[14]/td[6]/form/li[2]/a/i
    Wait Until Element Is Visible    xpath=//*[@id="example1"]/tbody[1]/tr[14]/td[6]/form/li[2]/a/i
    Click Element    xpath=//*[@id="example1"]/tbody[1]/tr[13]/td[6]/form/li[2]/a/i     #Button Edit

Edit Research Project
    Edit Input Date    end_date=03-11-2026
    Select From List By Index    id=status    2
    Scroll Element Into View    xpath=/html/body/div/div/div/div/div/div/div/form/button
    Select Internal responsible     option=ชัยพล กีรติกสิกร
    Click Element    xpath=/html/body/div/div/div/div/div/div/div/form/button

Edit Input Date
    [Arguments]   ${end_date}
    Input Text    xpath=/html/body/div/div/div/div/div/div/div/form/div[3]/div/input    {end_date}


Select Head responsible
    [Arguments]    ${option}
    Click Element    id=select2-head0-container
    Click Element    xpath=//li[contains(@id, 'select2-head0-result') and text()='${option}']


Select Internal responsible
    [Arguments]    ${option}
    Click Element    id=select2-selUser0-container
    Click Element    xpath=//li[contains(@id, 'select2-selUser0-result') and text()='${option}']

Input External responsible
    Input Text    xpath=//*[@id="tb"]/tbody/tr[2]/td[1]/input    นาย
    Input Text    xpath=//*[@id="tb"]/tbody/tr[2]/td[2]/input    สมชาย
    Input Text    xpath=//*[@id="tb"]/tbody/tr[2]/td[3]/input    หาญอาสา

Login Page Should Be Open
    Title Should Be    Login Page

Go To Login Page
    Go To    ${LOGIN URL}
    Login Page Should Be Open

Input Username
    [Arguments]    ${username}
    Input Text    username_field    ${username}

Input Passwords
    [Arguments]    ${password}
    Input Text    password_field    ${password}

Submit Credentials
    Click Button    login_button

# Welcome Page Should Be Open
#     Location Should Be    ${WELCOME URL}
#     Title Should Be    Welcome Page
