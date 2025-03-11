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
    Click Element    xpath=//*[@id="sidebar"]/ul/li[6]/a     #Research Project
    Scroll Element Into View    xpath=//*[@id="example1"]/tbody[1]/tr[1]/td[6]/form/li[2]/a/i
    Wait Until Element Is Visible    xpath=//*[@id="example1"]/tbody[1]/tr[1]/td[6]/form/li[2]/a/i
    Click Element    xpath=//*[@id="example1"]/tbody[1]/tr[1]/td[6]/form/li[2]/a/i     #Button Edit

TC004: Edit Research Project

    
    Execute JavaScript    document.getElementById('Project_end').value = 'yyyy-mm-dd';
    Scroll Element Into View    xpath=/html/body/div/div/div/div/div/div/div/div/form/div[16]/button
    Sleep    3s
    Click Element    xpath=/html/body/div/div/div/div/div/div/div/div/form/div[16]/button
    Page Should Contain Element    xpath=//li[contains(text(), 'กรุณาเลือกวันที่สิ้นสุดโครงการ')]
