function addComparedValue() {
  showProcessingModal();

  $.ajax({
    url: '/validation/add-compared-value',
    type: 'POST',
    async: true,

    success: function (data) {
      setTimeout(function () {
        hideProcessingModal();
        $('#compared-values').append(data);
      }, 500);
    },
    error: function(xhr) {
     setTimeout(function () {
       hideProcessingModal();
       alert('Произошла ошибка.');
       console.log(xhr.responseText);
     }, 500);
    }
  });
  return false;
}

function showProcessingModal() {
  $('#processingModal').modal({
    'show': true,
    'keyboard': false,
    'backdrop': 'static'
  });
}

function hideProcessingModal() {
  $('#processingModal').modal('hide');
}

function addQuestionnaire() {
  showProcessingModal();

  $.ajax({
    url: '/validation/add-questionnaire',
    type: 'POST',
    async: true,

    success: function (data) {
      setTimeout(function () {
        hideProcessingModal();
        $('#questionnaires').append(data);
      }, 500);
    },
    error: function (xhr) {
      setTimeout(function () {
        hideProcessingModal();
        alert('Произошла ошибка.');
        console.log(xhr.responseText);
      }, 500);
    }
  });
  return false;
}

function saveValidation() {
  $(document).ready(function () {
    $('#create-validation-btn').click(function (e) {
      e.preventDefault();

      var validation = {};
      // проверяемый ответ
      validation.title = $('#create_validation_title').val();

      if (validation.title.length <= 0) {
        alert('Заполните наименование контроля.');
        return false;
      }

      validation.answer = {};
      validation.answer.code = $('#create_validation_answerCode').val();

      if (validation.answer.code.length <= 0) {
        alert('Укажите код проверяемого ответа.');
        return false;
      }

      validation.answer.typeId = $('#create_validation_answerType').val();
      validation.answer.indicatorId = $('#create_validation_answerIndicator').val();
      // сравниваемые значения
      validation.comparedValues = {};
      validation.comparedValues.compareOperatorId = $('#create_validation_compareOperator').val();
      validation.comparedValues.values = [];

      validation.comparedValues.values.push({
        logicOperatorId: null,
        value: $('#create_validation_comparedValue').val(),
        typeId: $('#create_validation_comparedValueType').val(),
        inSameSection: $('#create_validation_comparedValueInSameSection').is(':checked')
      });

      var _comparedValues = $('.compared-value');
      for (var i = 0; i < _comparedValues.length; i++) {
        validation.comparedValues.values.push({
          logicOperatorId: $(_comparedValues[i]).find('select.compared-value-operator-id').first().val(),
          value: $(_comparedValues[i]).find('input.compared-value-input').first().val(),
          typeId: $(_comparedValues[i]).find('select.compared-value-type-id').first().val(),
          inSameSection: $(_comparedValues[i]).find('input.cv-in-same-section:checked').first().is(':checked')
        });
      }
      // связный ответ
      validation.relatedAnswer = {};
      var _relatedAnswer = $('#create_validation_relAnswerCode').val();
      if (_relatedAnswer != null && _relatedAnswer.length > 0) {
        validation.relatedAnswer.code = _relatedAnswer;
        validation.relatedAnswer.compareOperatorId = $('#create_validation_relAnswerCompareOperator').val();
        validation.relatedAnswer.value = $('#create_validation_relAnswerValue').val();
        validation.relatedAnswer.typeId = $('#create_validation_relAnswerType').val();
        validation.relatedAnswer.inSameSection = $('#create_validation_inSameSection').is(':checked');
      } else {
        validation.relatedAnswer = null;
      }
      // опросники
      validation.questionnaires = [];
      validation.questionnaires.push($('#create_validation_questionnaireId').val());

      var _questionnaires = $('.questionnaire');
      for (var q = 0; q < _questionnaires.length; q++) {
        validation.questionnaires.push(
          _questionnaires[q].getElementsByClassName('questionnaire-id')[0].value
        );
      }

      $.ajax({
        url: '/validation/create',
        type: 'POST',
        dataType: 'text',
        data: JSON.stringify(validation),

        success: function (response) {
          response = JSON.parse(response);
          if (response.success) {
            window.location.href = '/validation';
            alert('Валидация создана успешно.');
          } else {
            alert('Произошла ошибка. Попробуйте еще раз.');
            console.log(response.message);
          }
        },
        error: function (xhr) {
          alert('Произошла ошибка. Перезагрузите страницу и попробуйте еще раз.');
          console.log(xhr.responseText);
        }
      });
    });
  });
}

function handleRemoveValidationBtnClick() {
    $(document).ready(function () {
        $('.remove-validation').click(function () {
            let confirmed = confirm('Вы уверены, что хотите удалить контроль?');
            if (confirmed) {
              showProcessingModal();
              let validationId = $(this).data('id');

              $.ajax({
                url: '/validation/delete',
                type: 'POST',
                data: { 'id': validationId },

                success: function (response) {
                  setTimeout(function () {
                    hideProcessingModal();
                    if (response.success) {
                      $('#validation-' + validationId).remove();
                      alert('Контроль успешно удален.');
                    } else {
                      alert('Произошла ошибка. Перезагрузите страницу и попробуйте еще раз.');
                      console.log(response.message);
                    }
                  }, 500)
                },
                error: function (xhr) {
                  setTimeout(function () {
                    hideProcessingModal();
                    alert('Произошла ошибка. Перезагрузите страницу и попробуйте еще раз.');
                    console.log(xhr.responseText);
                  }, 500);
                }
              });
            }
        });
    });
}

function goToPage() {
    $(document).ready(function () {
        $('#go-to-page-btn').click(function () {
            let page = $('#page-value').val();
            let questionnaireId = $(this).data('id');

            window.location.href='/questionnaire/' + questionnaireId + '/errors/' + page;
        })
    })
}

function startValidate(deleteCurrentErrors = true, offset = 0) {
    $('#start-validate-btn').attr('disabled', 'disabled');
    let questionnaireId = $('#validate_questionnaire').val();
    let data = {
        'questionnaireId': questionnaireId,
        'offset': offset,
        'deleteCurrentErrors': deleteCurrentErrors
    };

    let progressIndicator = $('#progress-indicator');
    if (!progressIndicator.length) {
        let alertBlock = document.createElement('div');
        alertBlock.className = 'alert alert-primary';
        alertBlock.id = 'progress-indicator';
        alertBlock.setAttribute('role', 'alert');
        alertBlock.appendChild(document.createTextNode('Прогресс (%): 0/100'));

        $('#validate-block').prepend(alertBlock);
    }

    $.ajax({
        url: '/validate/start',
        type: 'POST',
        data: JSON.stringify(data),

        success: function (response) {
            if (response.completed) {
                window.location.href='/questionnaire/' + questionnaireId + '/errors';
            } else {
                let allRowsCount = response.allRowsCount;
                let checkedRowsPercent = (offset * 100) / allRowsCount;
                $('#progress-indicator').text('Прогресс (%): ' + Math.round(checkedRowsPercent) + '/100');

                startValidate(false, offset += 1000);
            }
        },

        error: function (xhr) {
            alert('Произошла ошибка. Перезагрузите страницу и попробуйте еще раз.');
            console.log(xhr.responseText);
        }
    });
}

function doNotSaveOnEnter() {
    $(document).ready(function() {
        $(window).keydown(function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                return false;
            }
        });
    });
}

function removeDivById(id) {
  $('#' + id).remove();
}