*** Settings ***
Documentation
Resource    ../../resource/resource.robot
Suite Teardown    Close Browser


*** Test Cases ***
TC001: Open Login Page
    Open Browser To Login Page
   
TC002: Login Success
    Input Username and Password    username=jutaum@kku.ac.th    password=123456789
    Click Element    xpath=/html/body/div/div[2]/div[2]/form/div[4]/button  #Login button
    Page Should Contain    Dashboard

TC003: Go to Research Project Page
    Click Element    xpath=//*[@id="sidebar"]/ul/li[6]/a/span
    Scroll Element Into View    xpath=//*[@id="example1"]/tbody[1]/tr[4]/td[6]/form/li[3]/button/i
    Click Element    xpath=//*[@id="example1"]/tbody[1]/tr[4]/td[6]/form/li[3]/button/i
    Sleep    2s
    Click Element    xpath=/html/body/div[2]/div/div[4]/div[2]/button
    Sleep    2s
    Click Element    xpath=/html/body/div[2]/div/div[3]/div/button
    Wait Until Element Is Visible    xpath=//p[contains(text(), 'ลบโครงการวิจัยสำเร็จ')]
    Page Should Contain Element   xpath=//p[contains(text(), 'ลบโครงการวิจัยสำเร็จ')]