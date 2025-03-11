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
    Scroll Element Into View    xpath=//*[@id="example1"]/tbody[1]/tr[4]/td[6]/form/li[2]/a/i
    Wait Until Element Is Visible    xpath=//*[@id="example1"]/tbody[1]/tr[4]/td[6]/form/li[2]/a/i
    Click Element    xpath=//*[@id="example1"]/tbody[1]/tr[4]/td[6]/form/li[2]/a/i 

TC004: Edit Research Project

    Execute JavaScript    document.getElementById('Project_end').value = '2025-03-12';
    Sleep    4s
    Scroll Element Into View    xpath=/html/body/div/div/div/div/div/div/div/div/form/div[16]/button
    Click Element    xpath=//*[@id="add-btn2"]
    Sleep    2s
     # Open the dropdown
    Click Element    id=select2-selUser1-container
    Wait Until Element Is Visible    xpath=//li[contains(@id, 'select2-selUser1-result') and text()='ปัญญาพล หอระตะ']
    
    Select From List By Index    id=status    2

    # Select the desired option
    Click Element    xpath=//li[contains(@id, 'select2-selUser1-result') and text()='ปัญญาพล หอระตะ']
    
    Sleep    3s
    Scroll Element Into View    xpath=/html/body/div/div/div/div/div/div/div/div/form/div[16]/button
    Click Element    xpath=/html/body/div/div/div/div/div/div/div/div/form/div[16]/button
    Page Should Contain Element    xpath=//p[contains(text(), 'อัปเดตโครงการวิจัยสำเร็จ')]

TC005: Show Edit Research Project
    Scroll Element Into View    xpath=//*[@id="example1"]/tbody[1]/tr[4]/td[6]/form/li[1]/a
    Wait Until Element Is Visible    xpath=//*[@id="example1"]/tbody[1]/tr[4]/td[6]/form/li[1]/a    timeout=2s
    Click Element    xpath=//*[@id="example1"]/tbody[1]/tr[4]/td[6]/form/li[1]/a
    
    Page Should Contain Element   xpath=//p[contains(text(), 'การทวนสอบเชิงรูปนัยด้วยเทคนิคการวิเคราะห์ปริภูมิสถานะแบบแอลเอสทีเอ็ม')]
    Page Should Contain Element   xpath=//p[contains(text(), '12/03/2025')]
    Page Should Contain Element   xpath=//p[contains(text(), '12/03/2025')]
    Page Should Contain Element   xpath=//p[contains(text(), 'ทุนภายใน')]
    Page Should Contain Element   xpath=//p[contains(text(), 'งบประมาณมหาวิทยาลัย / วิจัยใหม่ / งานวิจัยประยุกต์')]
    Page Should Contain Element   xpath=//p[contains(text(), '2024')]
    Page Should Contain Element   xpath=//p[contains(text(), '153,775.00 บาท')]
    Page Should Contain Element   xpath=//p[contains(text(), 'วิทยาลัยการคอมพิวเตอร์')]
    Page Should Contain Element   xpath=//p[contains(text(), 'ปิดโครงการ')]
    Page Should Contain Element   xpath=//p[contains(text(), 'ผศ.ดร. ชานนท์ เดชสุภา')]
