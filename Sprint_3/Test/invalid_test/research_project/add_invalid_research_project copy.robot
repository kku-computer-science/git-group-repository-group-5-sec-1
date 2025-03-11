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
    Input Text    xpath=/html/body/div/div/div/div/div/div/div/div/form/div[1]/div/input    พัฒนาระบบนำ CI/CD    
    Sleep    1s
    
    # Set the start date using JavaScript
    Execute JavaScript    document.getElementById('Project_start').value = '2025-03-12';
    Sleep    1s
    
    Select From List By Index    id=funds_type    1
    Sleep    1s

    Select From List By Index   id=funds_category    1
    Sleep    1s
    Select From List By Index    id=funds    1    
    
    #year
    Input Text    xpath=/html/body/div/div/div/div/div/div/div/div/form/div[6]/div/input    2025     
    
    Sleep    1s

    #budget
    Input Text    xpath=/html/body/div/div/div/div/div/div/div/div/form/div[7]/div[1]/input    200000    
    Click Element    id=show_budget_checkbox

    Sleep    1s
    

    Select From List By Index    id=status    1
    Scroll Element Into View    xpath=/html/body/div/div/div/div/div/div/div/div/form/div[16]/button
    Sleep    1s
    Select Head responsible    option=งามนิจ อาจอินทร์
    Sleep    1s
    Select Internal responsible     option=จักรชัย โสอินทร์
    Click Button    xpath=/html/body/div/div/div/div/div/div/div/div/form/div[16]/button

    # Verify the success message
    Page Should Contain Element    xpath=//li[contains(text(), 'กรุณาเลือกวันที่สิ้นสุดโครงการ')]
    Sleep    5s