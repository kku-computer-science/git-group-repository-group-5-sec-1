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

TC003: Go To Research Project Page
    Go To Research Project

TC004: Add Research Project
    
    #Project Name
    Input Text    xpath=/html/body/div/div/div/div/div/div/div/div/form/div[1]/div/input    การทวนสอบเชิงรูปนัยด้วยเทคนิคการวิเคราะห์ปริภูมิสถานะแบบแอลเอสทีเอ็ม

    Sleep    1s
    
    # Set the start date using JavaScript
    Execute JavaScript    document.getElementById('Project_start').value = '2025-03-12';
    Sleep    1s

    # Set the end date using JavaScript
    Execute JavaScript    document.getElementById('Project_end').value = '2026-03-12';
    Sleep    1s
    
    Select From List By Index    id=funds_type    2
    Sleep    1s

    Select From List By Index   id=funds_category    17
    Sleep    1s
    Select From List By Index    id=funds    1    
    
    #year
    Input Text    xpath=/html/body/div/div/div/div/div/div/div/div/form/div[6]/div/input    2024     
    
    Sleep    1s

    #budget
    Input Text    xpath=/html/body/div/div/div/div/div/div/div/div/form/div[7]/div[1]/input    153775
    Click Element    id=show_budget_checkbox

    Sleep    1s 

    Select From List By Index    id=status    1
    Scroll Element Into View    xpath=/html/body/div/div/div/div/div/div/div/div/form/div[16]/button
    Sleep    1s
    Select Head responsible    option=ชานนท์ เดชสุภา
    Sleep    1s
    Click Button    xpath=/html/body/div/div/div/div/div/div/div/div/form/div[16]/button

    # Verify the success message
    Page Should Contain Element    xpath=//p[contains(text(), 'สร้างโครงการวิจัยสำเร็จ')]
    Sleep    5s

TC005: Check Research Project Detial
    Scroll Element Into View    xpath=//*[@id="example1"]/tbody[1]/tr[4]/td[6]/form/li[1]/a
    Wait Until Element Is Visible    xpath=//*[@id="example1"]/tbody[1]/tr[4]/td[6]/form/li[1]/a    timeout=2s
    Click Element    xpath=//*[@id="example1"]/tbody[1]/tr[4]/td[6]/form/li[1]/a
    
    Page Should Contain Element   xpath=//p[contains(text(), 'การทวนสอบเชิงรูปนัยด้วยเทคนิคการวิเคราะห์ปริภูมิสถานะแบบแอลเอสทีเอ็ม')]
    Page Should Contain Element   xpath=//p[contains(text(), '12/03/2025')]
    Page Should Contain Element   xpath=//p[contains(text(), '12/03/2026')]
    Page Should Contain Element   xpath=//p[contains(text(), 'ทุนภายใน')]
    Page Should Contain Element   xpath=//p[contains(text(), 'งบประมาณมหาวิทยาลัย / วิจัยใหม่ / งานวิจัยประยุกต์')]
    Page Should Contain Element   xpath=//p[contains(text(), '2024')]
    Page Should Contain Element   xpath=//p[contains(text(), '153,775.00 บาท')]
    Page Should Contain Element   xpath=//p[contains(text(), 'วิทยาลัยการคอมพิวเตอร์')]
    Page Should Contain Element   xpath=//p[contains(text(), 'ดำเนินการ')]
    Page Should Contain Element   xpath=//p[contains(text(), 'ผศ.ดร. ชานนท์ เดชสุภา')]



