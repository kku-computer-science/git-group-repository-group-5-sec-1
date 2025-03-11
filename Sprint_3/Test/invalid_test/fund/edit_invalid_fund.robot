*** Settings ***
Documentation
Resource    ../../resource/resource.robot
Suite Teardown    Close Browser

*** Test Cases ***

TC001: Open Login Page
    Open Browser To Login Page
    
TC002: Login Success
    Input Username and Password    username=jutaum@kku.ac.th   password=123456789
    Click Element    xpath=/html/body/div/div[2]/div[2]/form/div[4]/button  #Login button
    Page Should Contain    Dashboard

TC003: Go to Fund Page
    #Manage Fund
    Click Element    xpath=//*[@id="sidebar"]/ul/li[5]/a/span
    Scroll Element Into View    xpath=//*[@id="example1"]/tbody/tr[12]/td[5]/form/li[2]/a
    Click Element   xpath=//*[@id="example1"]/tbody/tr[12]/td[5]/form/li[2]/a    #Button Edit

TC004: Edit Invalid Case
    Clear Element Text    xpath=/html/body/div/div/div/div/div/div/div/div/form/div[4]/div/input
    Click Element    xpath=/html/body/div/div/div/div/div/div/div/div/form/button
    Page Should Contain Element    xpath=//li[contains(text(), 'กรุณากรอกหน่วยงานที่สนับสนุน')]
    Sleep    5s
