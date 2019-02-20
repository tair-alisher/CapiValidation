function addComparedValue() {
  $.ajax({
    url: '/validation/add-compared-value',
    type: 'POST',
    async: true,

    success: function (data) {
      $('#compared-values').append(data);
    },
    error: function(xhr) {
      alert('Ajax request failed.');
      console.log(xhr.responseText);
    }
  });
  return false;
}

function addQuestionnaire() {
  $.ajax({
    url: '/validation/add-questionnaire',
    type: 'POST',
    async: true,

    success: function (data) {
      $('#questionnaires').append(data);
    },
    error: function (xhr) {
      alert('Ajax request failed.');
      console.log(xhr.responseText);
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
      validation.answer = {};
      validation.answer.code = $('#create_validation_answerCode').val();
      validation.answer.typeId = $('#create_validation_answerType').val();
      validation.answer.indicatorId = $('#create_validation_answerIndicator').val();
      // сравниваемые значения
      validation.comparedValues = {};
      validation.comparedValues.compareOperatorId = $('#create_validation_compareOperator').val();
      validation.comparedValues.values = [];

      validation.comparedValues.values.push({
        logicOperatorId: null,
        value: $('#create_validation_comparedValue').val(),
        typeId: $('#create_validation_comparedValueType').val()
      });

      var _comparedValues = $('.compared-value');
      for (var i = 0; i < _comparedValues.length; i++) {
        validation.comparedValues.values.push({
          logicOperatorId: _comparedValues[i].getElementsByClassName('compared-value-operator-id')[0].value,
          value: _comparedValues[i].getElementsByClassName('compared-value-input')[0].value,
          typeId: _comparedValues[i].getElementsByClassName('compared-value-type-id')[0].value
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
          var parsedResponse = JSON.parse(response);
          if (parsedResponse.success) {
            window.location.href = '/validation';
            alert('Валидация создана успешно.');
          } else {
            alert('Произошла ошибка. Попробуйте еще раз.');
            console.log(parsedResponse.message);
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
            var validationId = $(this).data('id');

            $.ajax({
                url: '/validation/delete',
                type: 'POST',
                data: { 'id': validationId },

                success: function (response) {
                    if (response.success) {
                        $('#validation-' + validationId).remove();
                        alert('Контроль успешно удален.');
                    } else {
                        alert('Произошла ошибка. Перезагрузите страницу и попробуйте еще раз.');
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

function goToPage() {
    $(document).ready(function () {
        $('#go-to-page-btn').click(function () {
            var page = $('#page-value').val();
            var questionnaireId = $(this).data('id');

            window.location.href='/questionnaire/' + questionnaireId + '/errors/' + page;
        })
    })
}