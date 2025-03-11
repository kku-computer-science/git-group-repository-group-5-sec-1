*** Settings ***
Documentation     A resource file with reusable keywords and variables.
...
...               The system specific keywords created here form our own
...               domain specific language. They utilize keywords provided
...               by the imported SeleniumLibrary.
Library           SeleniumLibrary

*** Variables ***
${BROWSER}        Chrome
${DELAY}          0
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
