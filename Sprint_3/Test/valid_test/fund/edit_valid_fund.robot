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
    Scroll Element Into View    xpath=//*[@id="example1"]/tbody/tr[13]/td[5]/form/li[2]/a
    Click Element    xpath=//*[@id="example1"]/tbody/tr[13]/td[5]/form/li[2]/a  #Button Edit
    
TC004: Edit Fund
    Input Text    xpath=/html/body/div/div/div/div/div/div/div/div/form/div[3]/div/input    งบประมาณมหาวิทยาลัย ประจำปี พ.ศ. 2569
    Click Element    xpath=/html/body/div/div/div/div/div/div/div/div/form/button
    Wait Until Page Contains Element    xpath=//p[contains(text(), 'แก้ไขทุนวิจัยสำเร็จ')]
    Page Should Contain Element    xpath=//p[contains(text(), 'แก้ไขทุนวิจัยสำเร็จ')]

TC005: Show Add Fund
    Scroll Element Into View    xpath=//*[@id="example1"]/tbody/tr[13]/td[5]/form/li[1]/a
    Click Element    xpath=//*[@id="example1"]/tbody/tr[13]/td[5]/form/li[1]/a
    Page Should Contain Element    xpath=//p[contains(text(), 'งบประมาณมหาวิทยาลัย ประจำปี พ.ศ. 2569')]
    Page Should Contain Element    xpath=//p[contains(text(), 'ทุนภายใน')]
    Page Should Contain Element    xpath=//p[contains(text(), 'งบประมาณมหาวิทยาลัย / วิจัยใหม่ / งานวิจัยพื้นฐาน')]
    Page Should Contain Element    xpath=//p[contains(text(), 'มหาวิทยาลัยขอนแก่น')]
    Page Should Contain Element    xpath=//p[contains(text(), 'จุฑารัตน์ อุ่มไธสง')]